<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/4/18
 * Time: 10:41 AM
 */

namespace KeywordRouter;


class MessageSender
{
    private $payload_str;

    private $payload;

    /**
     * MessageSender constructor.
     * @param string $str
     */
    public function __construct($str)
    {
        parse_str($str, $this->payload);

        $this->payload_str = $str;

        $this->payload['message_id'] = md5(uniqid(rand(), true));

        $this->save();
    }

    private function save()
    {
        $conn = Database::getInstance()->connection();

        $keyword = $this->payload['keyword'];
        $type = $this->payload['message_type'];
        $content = $this->payload_str;
        $msg_id = $this->payload['message_id'];

        $query = $conn->prepare("INSERT INTO `transaction_outgoing` (`keyword`, `type`, `msg_id`, `content`) VALUES ( ?, ?, ?, ?)");
        $query->bind_param('ssss', $keyword, $type, $msg_id, $content);
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

    function send()
    {
        $sms = new SMS($this->payload);

        if ($this->payload['message_type'] === SMS::MSG_TYPE_SEND) {
            $sms->send();
        } else {
            $sms->reply();
        }
    }


}