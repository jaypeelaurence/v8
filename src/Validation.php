<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 2:36 PM
 */

namespace KeywordRouter;


class Validation
{
    /**
     * @var mysqli
     */
    private $conn;

    /**
     * Validation constructor.
     */
    public function __construct()
    {
        $this->conn = Database::getInstance()->connection();
    }

    function isMsisdnBlocked($msisdn)
    {
        $q = $this->conn->prepare('SELECT msisdn FROM blacklist_msisdn WHERE msisdn=?');
        $q->bind_param('s', $msisdn);
        $q->execute();

        if ($q->get_result()->num_rows) {
            $q->close();
            return TRUE;
        } else {
            $q->close();
            return FALSE;
        }
    }

    function isKeywordBlock($keyword)
    {

    }

    /**
     * @param $keyword
     * @return bool
     */
    function isValidKeyword($keyword)
    {
        $q = $this->conn->prepare('SELECT * FROM keyword WHERE name=?');
        $q->bind_param('s', $keyword);
        $q->execute();

        if ($q->get_result()->num_rows) {
            $q->close();
            return TRUE;
        } else {
            $q->close();
            return FALSE;
        }

    }
}