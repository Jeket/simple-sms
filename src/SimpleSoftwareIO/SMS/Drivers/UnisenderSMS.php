<?php namespace SimpleSoftwareIO\SMS\Drivers;

use GuzzleHttp\Client;
use SimpleSoftwareIO\SMS\Drivers\AbstractSMS;
use SimpleSoftwareIO\SMS\Drivers\DriverInterface;
use SimpleSoftwareIO\SMS\IncomingMessage;
use SimpleSoftwareIO\SMS\OutgoingMessage;

class UnisenderSMS extends AbstractSMS implements DriverInterface
{

    /**
     * The Guzzle HTTP Client
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The API's URL.
     *
     * @var string
     */
    protected $apiBase = 'http://api.unisender.com/ru/api';

    /**
     * The API's URL without modifications.
     *
     * @var string
     */
    protected $apiOrigin = '';

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiOrigin = $this->apiBase;
    }

    /**
     * Creates many IncomingMessage objects and sets all of the properties.
     *
     * @param $rawMessage
     * @return mixed
     */
    protected function processReceive($rawMessage)
    {
        throw new \RuntimeException('Unisender does not support Inbound API Calls.');
    }

    /**
     * Resets $apiBase to its origin state
     */
    protected function resetBase()
    {
        $this->apiBase = $this->apiOrigin;
    }

    /**
     * Sends a SMS message
     *
     * @parma OutgoingMessage $message The message class.
     * @param OutgoingMessage $message
     * @return false|string
     */
    public function send(OutgoingMessage $message)
    {
        $composedMessage = $message->composeMessage();

        $data = [
            'phone'     => implode(',', $message->getTo()),
            'text'   => $composedMessage,
            'sender'   => $message->getFrom()
        ];

        $this->resetBase();
        $this->buildCall('/sendSms');
        $this->buildBody($data);

        $raw = (string) $this->postRequest()->getBody();

        return $raw;
    }

    /**
     * Checks the server for messages and returns their results.
     *
     * @param array $options
     * @return array
     */
    public function checkMessages(Array $options = array())
    {
        throw new \RuntimeException('Unisender does not support Inbound API Calls.');
    }

    /**
     * Check message status.
     *
     * @param $messageId
     * @return IncomingMessage
     */
    public function getMessage($messageId)
    {
        $data = [
            'sms_id' => $messageId
        ];

        $this->resetBase();
        $this->buildCall('/checkSms');
        $this->buildBody($data);

        $raw = (string) $this->postRequest()->getBody();

        return $raw;
    }

    /**
     * Receives an incoming message via REST call.
     *
     * @param $raw
     * @return \SimpleSoftwareIO\SMS\IncomingMessage
     */
    public function receive($raw)
    {
        throw new \RuntimeException('Unisender does not support Inbound API Calls.');
    }
}
