<?php

declare(strict_types=1);

namespace Imi\Workerman\Http\Message;

use Imi\Server\Http\Message\Request;
use Imi\Util\Stream\MemoryStream;
use Imi\Util\Uri;
use Workerman\Connection\TcpConnection;
use Workerman\Worker;

class WorkermanRequest extends Request
{
    /**
     * Workerman 的 http 请求对象
     *
     * @var \Workerman\Protocols\Http\Request
     */
    protected \Workerman\Protocols\Http\Request $workermanRequest;

    /**
     * Workerman 的 Worker 对象
     *
     * @var \Workerman\Worker
     */
    protected Worker $worker;

    public function __construct(Worker $worker, TcpConnection $connection, \Workerman\Protocols\Http\Request $request)
    {
        $this->workermanRequest = $request;
        $this->worker = $worker;
        $this->connection = $connection;
    }

    /**
     * 初始化协议版本.
     *
     * @return void
     */
    protected function initProtocolVersion()
    {
        $this->protocolVersion = $this->workermanRequest->protocolVersion();
    }

    /**
     * 初始化 headers.
     *
     * @return void
     */
    protected function initHeaders()
    {
        $this->mergeHeaders($this->workermanRequest->header());
    }

    /**
     * 初始化 body.
     *
     * @return void
     */
    protected function initBody()
    {
        $this->body = new MemoryStream($this->workermanRequest->rawBody());
    }

    /**
     * 初始化 uri.
     *
     * @return void
     */
    protected function initUri()
    {
        $worker = $this->worker;
        $isSSL = 'ssl' === $worker->transport;
        $scheme = $isSSL ? 'https' : 'http';
        $workerRequest = $this->workermanRequest;

        $get = $workerRequest->get();

        $this->uri = Uri::makeUri($workerRequest->host(), $workerRequest->path(), null === $get ? '' : (http_build_query($get, '', '&')), null, $scheme);
    }

    /**
     * 初始化 method.
     *
     * @return void
     */
    protected function initMethod()
    {
        $this->method = $this->workermanRequest->method();
    }

    /**
     * 初始化 server.
     *
     * @return void
     */
    protected function initServer()
    {
        $this->server = [];
    }

    /**
     * 初始化请求参数.
     *
     * @return void
     */
    protected function initRequestParams()
    {
        $request = $this->workermanRequest;
        $this->get = $request->get();
        $this->post = $request->post();
        $this->cookies = $request->cookie() ?? [];
        $this->request = null;
    }

    /**
     * 初始化上传文件.
     *
     * @return void
     */
    protected function initUploadedFiles()
    {
        $this->setUploadedFiles($this->workermanRequest->file());
    }

    /**
     * Get workerman 的 http 请求对象
     *
     * @return \Workerman\Protocols\Http\Request
     */
    public function getWorkermanRequest(): \Workerman\Protocols\Http\Request
    {
        return $this->workermanRequest;
    }

    /**
     * Get workerman 的 Worker 对象
     *
     * @return \Workerman\Worker
     */
    public function getWorker(): Worker
    {
        return $this->worker;
    }

    /**
     * Get workerman 连接对象
     *
     * @return TcpConnection
     */
    public function getConnection(): TcpConnection
    {
        return $this->connection;
    }
}