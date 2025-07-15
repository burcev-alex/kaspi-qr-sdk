<?php

declare(strict_types=1);

namespace KaspiQrSdk\Request;

use KaspiQrSdk\KaspiScheme;
use KaspiQrSdk\Exception\Factory;
use KaspiQrSdk\Exception\KaspiSdkException;
use KaspiQrSdk\Response\ResponseHandler;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

/**
 * Абстрактный класс для запросов к Kaspi QR API
 */
abstract class AbstractRequest
{
    protected string $scheme;
    protected string $baseUrl;
    protected ?string $organizationBin;
    protected ?string $deviceToken;
    protected Client $client;
    protected bool $debugMode;
    protected ?LoggerInterface $logger;

    public function __construct(
        Client $client,
        string $baseUrl,
        string $scheme,
        ?string $organizationBin,
        ?string $deviceToken,
        ?LoggerInterface $logger = null
    ) {
        $this->client = $client;
        $this->debugMode = $logger !== null;
        $this->scheme = $scheme;
        $this->baseUrl = $baseUrl;
        $this->organizationBin = $organizationBin;
        $this->deviceToken = $deviceToken;
        $this->logger = $logger;
    }

    /**
     * Выполняет HTTP-запрос и возвращает ответ в виде массива
     * @param Request $request
     * @return array
     * @throws KaspiSdkException
     */
    protected function makeRequest(Request $request): array
    {
        try {
            $response = $this->client->send($request);
            $content = $response->getBody()->getContents();
            $data = json_decode($content, true);
            if (!isset($data['StatusCode']) || (int)$data['StatusCode'] !== 0) {
                $statusMessage = $this->getMessageByStatusCode((int)($data['StatusCode'] ?? 0));
                throw new KaspiSdkException('StatusCode: ' . ($data['StatusCode'] ?? 'unknown') . ' ' . $statusMessage, 500);
            }
        } catch (RequestException $exception) {
            $responseHandler = new ResponseHandler($exception->getResponse());
            $strMessage = "Request ERROR (" . $request->getUri()->getPath() . "): ";
            $strMessage .= $exception->getMessage();
            $strMessage .= " / " . $responseHandler->getContents();
            $strMessage .= " / " . $exception->getCode();
            $strMessage .= " / " . $exception->getTraceAsString();
            if ($this->logger) {
                $this->logger->warning($strMessage);
            }
            throw Factory::createFromResponse($responseHandler);
        }
        return $data;
    }

    public function isDebugMode(): bool
    {
        return $this->debugMode;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getOrganizationBin(): ?string
    {
        return $this->organizationBin;
    }

    public function getBaseUrl(string $path): string
    {
        return $this->baseUrl . "/" . $path;
    }

    /**
     * Возвращает текстовое описание по коду статуса
     * @param int $statusCode
     * @return string|null
     */
    private function getMessageByStatusCode(int $statusCode): ?string
    {
        $items = [
            -10000 => 'Отсутствует сертификат клиента',
            -1501 => 'Устройство с заданным идентификатором не найдено',
            -1502 => 'Устройство не активно (отключено или удалено)',
            -1503 => 'Устройство уже добавлено в другую торговую точку',
            -1601 => 'Покупка не найдена',
            -14000002 => 'Отсутствуют торговые точки, необходимо создать торговую точку в приложении',
            -99000002 => 'Торговая точка не найдена',
            -99000005 => 'Сумма возврата не может превышать сумму покупки',
            -99000006 => 'Ошибка возврата, необходимо попробовать еще раз и при повторении ошибки',
            990000018 => 'Торговая точка отключена',
            990000026 => 'Торговая точка не принимает оплату с QR',
            990000028 => 'Указана неверная сумма операции',
            990000033 => 'Нет доступных методов оплаты',
            -99000001 => 'Покупка с заданным идентификатором не найдена',
            -99000002 => 'Торговая точка не найдена',
            -99000003 => 'Торговая точка покупки не соответствует текущему устройству',
            -99000011 => 'Невозможно вернуть покупку (несоответствующий статус покупки)',
            -99000020 => 'Частичный возврат невозможен',
            -999 => 'Сервис временно недоступен',
        ];
        return $items[$statusCode] ?? null;
    }

    /**
     * Подготавливает данные для отправки в Kaspi QR API
     * @param array $params
     * @return array
     */
    protected function getPrepareData(array $params): array
    {
        if ($this->getScheme() === KaspiScheme::STRONG->value) {
            $companyBin = $this->getOrganizationBin();
            if (is_null($companyBin)) {
                throw new KaspiSdkException('OrganizationBin is required for STRONG scheme');
            }
            $params['OrganizationBin'] = $companyBin;
        }
        return $params;
    }
}
