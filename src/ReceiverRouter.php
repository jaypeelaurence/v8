<?php
/**
 * Created by PhpStorm.
 * User: jhunnelpalomares
 * Date: 9/3/18
 * Time: 2:25 PM
 */

namespace KeywordRouter;

use GuzzleHttp\Client;


/**
 * This will receive MO sms.
 *
 * Class ReceiverRouter
 * @package KeywordRouter
 */
class ReceiverRouter extends Router
{
    /**
     * @var \mysqli
     */
    private $conn;

    /**
     * @var Keyword
     */
    private $keyword;

    /**
     * ReceiverRouter constructor.
     */
    public function __construct($data)
    {
        parent::__construct($data);

        $msisdn = Helper::formatMSISDN($this->payload['mobile_number'], Helper::MSISDN_TYPE_4);

        $validation = new Validation();

        if ($validation->isMsisdnBlocked($msisdn)) exit; // stop processing fraud numbers.

        $name = explode(' ', $this->payload['message'])[0];

        if (!$validation->isValidKeyword($name)) {
            // send sms reply
            $payload = array(
                'message_type' => SMS::MSG_TYPE_REPLY,
                'mobile_number' => $this->payload['mobile_number'],
                'request_id' => $this->payload['request_id'],
                'message' => 'Invalid keyword.',
                'keyword' => 'KR',
            );

            $sender = new MessageSender(http_build_query($payload));
            $sender->send();
            exit;
        }

        $this->keyword = new Keyword($name);

        $this->conn = Database::getInstance()->connection();

    }

    function createTransaction()
    {
        $keyword = strtoupper($this->keyword->getName());
        $type = $this->payload['message_type'];
        $content = $this->payload_str;
        $request_id = $this->payload['request_id'];

        $query = $this->conn->prepare("INSERT INTO `transaction` (`keyword`, `msg_type`, `request_id`, `content`) VALUES ( ?, ?, ?, ?)");
        $query->bind_param('ssss', $keyword, $type, $request_id, $content);
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

    /**
     * TODO: create transaction_complete schema, to save result.
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function deploy()
    {
        $client = new Client();

        $client->request('POST', $this->keyword->getToUrl(), ['form_params' => $this->payload]);
    }


}