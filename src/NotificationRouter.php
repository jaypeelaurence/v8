<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/4/18
 * Time: 9:40 AM
 */

namespace KeywordRouter;


use GuzzleHttp\Client;

class NotificationRouter extends Router
{

    /**
     * @var \mysqli
     */
    private $conn;

    /**
     * NotificationRouter constructor.
     */
    public function __construct($data)
    {
        parent::__construct($data);

        $this->conn = Database::getInstance()->connection();

    }

    function createTransaction()
    {
        $status = $this->payload['status'];
        $msg_id = $this->payload['message_id'];
        $content = $this->payload_str;

        $query = $this->conn->prepare("INSERT INTO `transaction_result` (`msg_id`, `status`, `content`) VALUES ( ?, ?, ?)");
        $query->bind_param('sss', $msg_id, $status, $content);
        $query->execute();

        if ($query->error) {
            $class_name = explode('\\', __CLASS__);
            $date = new \DateTime();
            $err = $date->format('Y-m-d h:i:s') . ' ERROR:' . $query->error . ' INPUT:' . $this->payload_str . ' CLASS:' . array_pop($class_name);

            $filename = SITE_ROOT . '/logs/error_transaction.log';
            $handle = fopen($filename, 'a') or die('Cannot open the file');

            if (filesize($filename)) {
                fwrite($handle, "\n" . $err);
            } else {
                fwrite($handle, $err);
            }

            fclose($handle);
        }

        $query->close();
    }

    function deploy()
    {
        $query = $this->conn->prepare('SELECT 
    kw.dn_url
FROM
    transaction_result AS tres
        LEFT JOIN
    transaction_outgoing AS tout ON tres.msg_id = tout.msg_id
        LEFT JOIN
    keyword AS kw ON tout.keyword = kw.name
WHERE
    tres.msg_id =? LIMIT 1');

        $query->bind_param('s', $this->payload['message_id']);
        $query->execute();
        $result = $query->get_result()->fetch_object();

        if ($result->dn_url) {
            $http = new Client();
            $http->request('POST', $result->dn_url, ['form_params' => $this->payload]);
        } else {
            // TODO: refactor after wyeth campaign
            file_put_contents('/tmp/v8Mo_NR_v2_29290633' . time(), $this->payload_str);
        }

        $query->close();

    }

}