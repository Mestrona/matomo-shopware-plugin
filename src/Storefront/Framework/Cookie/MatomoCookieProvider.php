<?php declare(strict_types=1);

namespace JinyaMatomo\Storefront\Framework\Cookie;

use Shopware\Storefront\Framework\Cookie\CookieProviderInterface;

class MatomoCookieProvider implements CookieProviderInterface
{
    /**
     * @var CookieProviderInterface
     */
    private $original;

    public function __construct(CookieProviderInterface $cookieProvider)
    {
        $this->original = $cookieProvider;
    }

    public function getCookieGroups(): array
    {
        $cookies = $this->original->getCookieGroups();

        foreach ($cookies as &$cookie) {
            if (!\is_array($cookie)) {
                continue;
            }

            if (!$this->isStatisticalCookieGroup($cookie)) {
                continue;
            }

            if (!\array_key_exists('entries', $cookie)) {
                continue;
            }

            $cookie['entries'][] = [
                'snippet_name' => 'matomo.cookie.name',
                'cookie' => 'matomo-enabled',
                'value' => 1,
            ];
        }

        return $cookies;
    }

    private function isStatisticalCookieGroup(array $cookie): bool
    {
        return (\array_key_exists('snippet_name', $cookie) && $cookie['snippet_name'] === 'cookie.groupStatistical');
    }
}
