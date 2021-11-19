<?php declare(strict_types=1);

namespace Somnambulist\Components\Validation;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use function array_filter;
use function array_keys;
use function array_merge;
use function array_values;
use const ARRAY_FILTER_USE_BOTH;

/**
 * Class DataBag
 *
 * @package    Somnambulist\Components\Validation
 * @subpackage Somnambulist\Components\Validation\DataBag
 */
class DataBag implements Countable, IteratorAggregate
{
    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    public function count()
    {
        return count($this->data);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function each(callable $callback): static
    {
        foreach ($this->data as $key => $value) {
            if (false === $callback($value, $key)) {
                break;
            }
        }

        return $this;
    }

    public function filter(?callable $callback): self
    {
        return new self(array_filter($this->data, $callback, ARRAY_FILTER_USE_BOTH));
    }

    public function get(?string $key, mixed $default = null): mixed
    {
        return Helper::arrayGet($this->data, $key, $default);
    }

    public function has(string $key): bool
    {
        return Helper::arrayHas($this->data, $key);
    }

    public function hasAnyOf(string ...$key): bool
    {
        foreach ($key as $test) {
            if ($this->has($test)) {
                return true;
            }
        }

        return false;
    }

    public function keys(): self
    {
        return new self(array_keys($this->data));
    }

    public function map(callable $callable): self
    {
        $keys  = array_keys($this->data);
        $items = array_map($callable, $this->data, $keys);

        return new self(array_combine($keys, $items));
    }

    public function merge(array $params): static
    {
        $this->data = array_merge($this->data, $params);

        return $this;
    }

    public function only(string ...$key): array
    {
        $ret = [];

        foreach ($key as $k) {
            $ret[$k] = $this->get($k);
        }

        return $ret;
    }

    public function set(string $key, mixed $value): static
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function unset(string $key): static
    {
        unset($this->data[$key]);

        return $this;
    }

    public function values(): self
    {
        return new self(array_values($this->data));
    }
}
