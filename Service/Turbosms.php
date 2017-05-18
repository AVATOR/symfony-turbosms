<?php

namespace AVATOR\TurbosmsBundle\Service;

use AVATOR\TurbosmsBundle\Entity\TurboSmsSent;
use Doctrine\ORM\EntityManager;
use SoapClient;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class Turbosms
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Debug mode
     *
     * @var bool
     */
    public $debug = false;

    /**
     * @var SoapClient
     */
    protected $client;

    /**
     * Wsdl url
     *
     * @var string
     */
    protected $wsdl = 'http://turbosms.in.ua/api/wsdl.html';

    /**
     * @var string
     */
    protected $lastSendMessageId = '';

    /**
     * @var array
     */
    protected $lastSendMessagesIds = [];

    /**
     * @var bool
     */
    protected $sendStatus;

    /**
     * Turbosms constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, DataCollectorTranslator $translator, ContainerInterface $container)
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->container = $container;
        $this->debug = $this->container->getParameter("avator_turbosms.debug");
        $this->repository = $em->getRepository("AVATOR\TurbosmsBundle\Entity\TurboSmsSent");
    }

    /**
     * Send sms and return array of message's ids in database
     *
     * @param string $text
     * @param $phones
     *
     * @return array
     *
     * @throws \Exception
     */
    public function send($text, $phones)
    {
        if (!is_array($phones)) {
            $phones = [$phones];
        }

        foreach ($phones as $phone) {
            if (!$phone) {
                continue;
            }

            $message = $this->sendMessage($text, $phone);
            $this->saveToDb($text, $phone, $message);
        }

        return $this->lastSendMessagesIds;
    }

    /**
     * Connect to Turbosms by Soap
     *
     * @return SoapClient
     * @throws \Exception
     */
    protected function connect()
    {
        var_dump($this->translator);exit("<br/>DELETE ME(EXIT())<br/>");
        if ($this->client) {
            return $this->client;
        }


        $login = $this->container->getParameter("avator_turbosms.login");
        $password = $this->container->getParameter("avator_turbosms.password");

        $client = new SoapClient($this->wsdl);
        if (!$login || !$password) {
            throw new \Exception($this->trans('Введите имя пользователя и пароль от Turbosms.'));
        }

        $result = $client->Auth([
            'login' => $login,
            'password' => $password,
        ]);

        if ($result->AuthResult . '' != 'Вы успешно авторизировались') {
            // @todo
            throw new \Exception($this->trans($result->AuthResult . ''));
        }

        $this->client = $client;

        return $this->client;
    }

    /**
     * Save sms to db
     *
     * @param string $text
     * @param string $phone
     * @param string $message
     *
     * @return bool
     */
    public function saveToDb($text, $phone, $message)
    {
        if (!$this->container->getParameter("avator_turbosms.save_to_db")) {
            return false;
        }

        $model = new TurboSmsSent();
        $model->setStatusMessage($message  . " " . ($this->debug ? $this->trans('(тестовый режим)') : ''));
        $model->setMessage($text);
        $model->setPhone($phone);
        if ($this->lastSendMessageId) {
            $model->setMessageId($this->lastSendMessageId);
        }
        $model->setStatus($this->sendStatus);

        $this->em->persist($model);
        $this->em->flush();

        if ((int)$model->getId()) {
            $this->lastSendMessagesIds[$model->getId()] = $this->lastSendMessageId;
        }

        return true;
    }

    /**
     * Get balance
     *
     * @return int
     */
    public function getBalance()
    {
        return $this->debug ? 0 : intval($this->getClient()->GetCreditBalance()->GetCreditBalanceResult);
    }

    /**
     * Get message status
     *
     * @param $messageId
     *
     * @return string
     */
    public function getMessageStatus($messageId)
    {
        if ($this->debug || !$messageId) {
            return '';
        }

        $result = $this->getClient()->GetMessageStatus(['MessageId' => $messageId]);

        return $result->GetMessageStatusResult;
    }

    /**
     * Get Soap client
     *
     * @return SoapClient
     * @throws \Exception
     */
    protected function getClient()
    {
        if (!$this->client) {
            return $this->connect();
        }

        return $this->client;
    }

    /**
     * @param $text
     * @param $phone
     * @return string
     */
    protected function sendMessage($text, $phone)
    {
//        Messages successfully sent
        $message = $this->trans('Сообщения успешно отправлено.');
        // set default status
        $this->sendStatus = true;
        // clear variable
        $this->lastSendMessageId = '';
        if ($this->debug) {
            return $message;
        }

        $result = $this->getClient()->SendSMS([
            'sender' => $this->container->getParameter("avator_turbosms.sender"),
            'destination' => $phone,
            'text' => $text
        ]);

        if (is_array($result->SendSMSResult->ResultArray) && !empty($result->SendSMSResult->ResultArray[1])) {
            $this->lastSendMessageId = $result->SendSMSResult->ResultArray[1];
        }

        if (empty($result->SendSMSResult->ResultArray[0]) ||
            $result->SendSMSResult->ResultArray[0] != 'Сообщения успешно отправлены'
        ) {
            $this->sendStatus = false;
            // @todo delete preg_replace
            $message = preg_replace('/%error%/i', $result->SendSMSResult->ResultArray,
                $this->trans('Сообщения не отправлено (ошибка: "%error%").'));
        }

        return $message;
    }

    /**
     * @param string $message
     * @param array  $params
     *
     * @return string
     */
    private function trans($message, array $params = array())
    {
        return $this->translator->trans($message, $params, 'messages');
    }

}
