<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 10:30 PM
 */

namespace KeywordRouter;


class Keyword
{

    private $name;
    private $dn_url;
    private $to_url;

    /**
     * Keyword constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;

        $conn = Database::getInstance()->connection();

        $q = $conn->prepare('SELECT dn_url, to_url FROM keyword WHERE name=?');
        $q->bind_param('s', $name);
        $q->execute();

        $result = $q->get_result()->fetch_object();

        $this->dn_url = $result->dn_url;
        $this->to_url = $result->to_url;

        $q->close();

    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDnUrl()
    {
        return $this->dn_url;
    }

    /**
     * @return mixed
     */
    public function getToUrl()
    {
        return $this->to_url;
    }

}