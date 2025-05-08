<?php

namespace Bibo\Core\Wrapper;

class CurlWrapper
{
    private $ch;

    private $multiHandle;

    private $active;

    public function __construct()
    {
        $this->ch = curl_init();
        $this->multiHandle = curl_multi_init();
        $this->active = 0;
    }

    public function setOption(int $option, $value): self
    {
        curl_setopt($this->ch, $option, $value);
        return $this;
    }

    public function setUrl(string $url): self
    {
        $this->setOption(CURLOPT_URL, $url);

        return $this;
    }

    public function setMethod(string $method): self
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, $method);

        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $formattedHeaders = [];
        foreach ($headers as $name => $value) {
            $formattedHeaders[] = "$name: $value";
        }
        $this->setOption(CURLOPT_HTTPHEADER, $formattedHeaders);

        return $this;
    }

    public function setPostFields($fields): self
    {
        $this->setOption(CURLOPT_POSTFIELDS, $fields);

        return $this;
    }

    public function execute(): string
    {
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLOPT_HEADER, false);

        $this->active = curl_multi_exec($this->multiHandle, $this->active);

        if ($this->active > 0) {
            $response = curl_multi_getcontent($this->multiHandle);
            return $response;
        }

        return '';
    }

    public function addHandle(): self
    {
        curl_multi_add_handle($this->multiHandle, $this->ch);
        return $this;
    }

    public function removeHandle(): self
    {
        curl_multi_remove_handle($this->multiHandle, $this->ch);
        return $this;
    }

    public function close(): void
    {
        curl_multi_close($this->multiHandle);
        curl_close($this->ch);
    }

    public function getInfo(int $option): mixed
    {
        return curl_getinfo($this->ch, $option);
    }

    public function getError(): string
    {
        return curl_error($this->ch);
    }

    public function getErrorNo(): int
    {
        return curl_errno($this->ch);
    }

    public function getMultiInfo(): array
    {
        return curl_multi_info_read($this->multiHandle);
    }

    public function getActive(): int
    {
        return $this->active;
    }

    public function getHandle()
    {
        return $this->ch;
    }

    public function getMultiHandle()
    {
        return $this->multiHandle;
    }

    public function setOptArray(array $options): self
    {
        curl_setopt_array($this->ch, $options);
        return $this;
    }

    public function setOpt(int $option, $value): self
    {
        curl_setopt($this->ch, $option, $value);
        return $this;
    }

    public function setOptArrayMulti(array $options): self
    {
        curl_multi_setopt($this->multiHandle, $options);
        return $this;
    }

    public function setOptMulti(int $option, $value): self
    {
        curl_multi_setopt($this->multiHandle, $option, $value);
        return $this;
    }

    public function setOptPostFields($fields): self
    {
        $this->setOption(CURLOPT_POSTFIELDS, $fields);
        return $this;
    }

    public function setOptReturnTransfer(bool $returnTransfer): self
    {
        $this->setOption(CURLOPT_RETURNTRANSFER, $returnTransfer);
        return $this;
    }

    public function setOptHeader(bool $header): self
    {
        $this->setOption(CURLOPT_HEADER, $header);
        return $this;
    }

    public function setOptFollowLocation(bool $followLocation): self
    {
        $this->setOption(CURLOPT_FOLLOWLOCATION, $followLocation);
        return $this;
    }

    public function setOptTimeout(int $timeout): self
    {
        $this->setOption(CURLOPT_TIMEOUT, $timeout);
        return $this;
    }

    public function setOptConnectTimeout(int $connectTimeout): self
    {
        $this->setOption(CURLOPT_CONNECTTIMEOUT, $connectTimeout);
        return $this;
    }

    public function setOptUserAgent(string $userAgent): self
    {
        $this->setOption(CURLOPT_USERAGENT, $userAgent);
        return $this;
    }

    public function setOptProxy(string $proxy): self
    {
        $this->setOption(CURLOPT_PROXY, $proxy);
        return $this;
    }

    public function setOptProxyAuth(string $proxyAuth): self
    {
        $this->setOption(CURLOPT_PROXYAUTH, $proxyAuth);
        return $this;
    }

    public function setOptProxyUserPwd(string $proxyUserPwd): self
    {
        $this->setOption(CURLOPT_PROXYUSERPWD, $proxyUserPwd);
        return $this;
    }

    public function setOptProxyType(int $proxyType): self
    {
        $this->setOption(CURLOPT_PROXYTYPE, $proxyType);
        return $this;
    }

    public function setOptSslVerifyPeer(bool $sslVerifyPeer): self
    {
        $this->setOption(CURLOPT_SSL_VERIFYPEER, $sslVerifyPeer);
        return $this;
    }

    public function setOptSslVerifyHost(int $sslVerifyHost): self
    {
        $this->setOption(CURLOPT_SSL_VERIFYHOST, $sslVerifyHost);
        return $this;
    }

    public function setOptSslCert(string $sslCert): self
    {
        $this->setOption(CURLOPT_SSLCERT, $sslCert);
        return $this;
    }

    public function setOptSslKey(string $sslKey): self
    {
        $this->setOption(CURLOPT_SSLKEY, $sslKey);
        return $this;
    }

    public function setOptSslKeyPass(string $sslKeyPass): self
    {
        $this->setOption(CURLOPT_SSLKEYPASSWD, $sslKeyPass);
        return $this;
    }

    public function setOptSslCafile(string $sslCafile): self
    {
        $this->setOption(CURLOPT_CAINFO, $sslCafile);
        return $this;
    }

    public function setOptSslCapath(string $sslCapath): self
    {
        $this->setOption(CURLOPT_CAPATH, $sslCapath);
        return $this;
    }

    public function setOptSslCipherList(string $sslCipherList): self
    {
        $this->setOption(CURLOPT_SSL_CIPHER_LIST, $sslCipherList);
        return $this;
    }

    public function __destruct()
    {
        curl_multi_close($this->multiHandle);
        curl_close($this->ch);
    }
}
