<?php

/**
 * This file is part of the `tvi/monitor-bundle` project.
 *
 * (c) https://github.com/turnaev/monitor-bundle/graphs/contributors
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Tvi\MonitorBundle\Check\http\GuzzleHttpService;

use JMS\Serializer\Annotation as JMS;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Message\RequestInterface as GuzzleRequestInterface;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Tvi\MonitorBundle\Check\CheckAbstract;

/**
 * @JMS\ExclusionPolicy("all")
 *
 * @author Vladimir Turnaev <turnaev@gmail.com>
 */
class Check extends CheckAbstract
{
    /**
     * @var GuzzleHttpService
     */
    private $checker;

    /**
     * @param string|PsrRequestInterface|GuzzleRequestInterface $requestOrUrl
     *                                                                        The absolute url to check, or a
     *                                                                        fully-formed request instance
     * @param array                                             $headers      An array of headers used to create the
     *                                                                        request
     * @param array                                             $options      An array of guzzle options to use when
     *                                                                        sending the request
     * @param int                                               $statusCode   The response status code to check
     * @param null                                              $content      The response content to check
     * @param null|GuzzleClientInterface                        $guzzle       Instance of guzzle to use
     * @param string                                            $method       The method of the request
     * @param mixed                                             $body         The body of the request (used for POST,
     *                                                                        PUT and DELETE requests)
     * @param bool                                              $setData      set data to result
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        $requestOrUrl,
        array $headers = [],
        array $options = [],
        $statusCode = 200,
        $content = null,
        $guzzle = null,
        $method = 'GET',
        $body = null,
        $setData = false)
    {
        $this->checker = new GuzzleHttpService($requestOrUrl, $headers, $options, $statusCode, $content, $guzzle, $method, $body, $setData);
    }

    /**
     * {@inheritdoc}
     */
    public function check()
    {
        return $this->checker->check();
    }
}
