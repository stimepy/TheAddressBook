<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04
 *  
 *  lib/class-birthday.php
 *  Object: Creates birthday list
 *
 *************************************************************/
// NOT DONE.
// Maybe this file should extent Contact. All the information such as names, dates, etc. should be
// determined by ID in the Contact object.
// Birthday class should only retrieve a list of ID's by date order and that way would determine
// which ID's to call in instances of Contact object.

global $globalUsers;

$globalUsers->checkForLogin();

class Birthday
{

    private function getBirthdayData($bdayInterval)
    {
        global $globalSqlLink;
        $select = 'id, CONCAT(firstname,\' \',lastname) AS fullname,
					   DATE_FORMAT(birthday, \'%M %e, %Y\') AS birthday,
                       MONTHNAME(birthday) AS month,
                       DAYOFMONTH(birthday) AS day,
                       YEAR(birthday) AS year,
					   (YEAR(NOW()) - YEAR(birthday) + (RIGHT(CURRENT_DATE,5)>RIGHT(birthday,5))) AS age,
				       (TO_DAYS((birthday + INTERVAL (YEAR(CURRENT_DATE)-YEAR(birthday) + (RIGHT(CURRENT_DATE,5)>RIGHT(birthday,5))) YEAR)) - TO_DAYS(CURRENT_DATE)) as daysAway';
        $where = 'birthday != \'\'
					AND (TO_DAYS((birthday + INTERVAL (YEAR(CURRENT_DATE)-YEAR(birthday) + (RIGHT(CURRENT_DATE,5)>RIGHT(birthday,5)) ) YEAR)) - TO_DAYS(CURRENT_DATE)) < ' . $bdayInterval . '
					AND hidden != 1';
        $globalSqlLink->SelectQuery($select, TABLE_CONTACT, $where, "ORDER BY daysAway ASC, age DESC");

        return $globalSqlLink->FetchQueryResult();

    }

    public function GetBirthday($options, $lang, $file_address)
    {

        $r_bday = $this->getBirthdayData($options->bdayInterval());
        $body['langbirth'] = $lang['BIRTHDAY_UPCOMING1'] . $options->bdayInterval() . $lang['BIRTHDAY_UPCOMING2'];
        $x = 0;

        foreach ($r_bday as $tbl_birthday) {
           $this-> fillInBirthday($tbl_birthday, $options, $lang, $file_address, $x,$body);
        }

    }

    private function fillInBirthday($tbl_birthday, $options, $lang, $file_address, $x,$body){
            $age = ($tbl_birthday['year'] > 0) ? "                    <TD CLASS=\"listEntry\">                       " . $tbl_birthday['age'] . " yrs                    </TD>" : "                    <TD CLASS=\"listEntry\">&nbsp;</TD>";
            $year = ($tbl_birthday['year'] > 0) ? ", " . $tbl_birthday['year'] : "";
            if ($options->getdisplayAsPopup() == 1) {
                $popupLink = " onClick=\"window.open('" . $file_address . "?id=" . $tbl_birthday['id'] . "','addressWindow','width=600,height=450,scrollbars,resizable,menubar,status'); return false;\" ";
            }

            $body['bithinfo'][$x] = "                  <tr>
                <TD CLASS=\"listEntry\"><A HREF=\"" . $file_address . "?id=" . $tbl_birthday['id'] . "\"" . $popupLink . ">" . stripslashes($tbl_birthday['fullname']) . "</A></TD>
                <TD CLASS=\"listEntry\">
                    " . $tbl_birthday['month'] . " " . $tbl_birthday['day'] . $year . "
                </TD>
        " . $age . "           
              </TR>";

    }

}
