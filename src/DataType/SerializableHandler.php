<?php

namespace Plank\Metable\DataType;

use Serializable;

/**
 * Handle serialization of Serializable objects.
 * @deprecated Use SerializeHandler instead.
 */
class SerializableHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDataType(): string
    {
        return 'serializable';
    }

    /**
     * {@inheritdoc}
     */
    public function canHandleValue(mixed $value): bool
    {
        return $value instanceof Serializable;
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue(mixed $value): string
    {
        return serialize($value);
    }

    /**
     * {@inheritdoc}
     */
    public function unserializeValue(string $serializedValue): mixed
    {
        $allowedClasses = config('metable.options.serializable.allowedClasses', false);
        return unserialize($serializedValue, ['allowed_classes' => $allowedClasses]);
    }

    public function getNumericValue(mixed $value): null|int|float
    {
        return null;
    }

    public function getStringValue(mixed $value): null|string
    {
        if (!config('metable.indexComplexDataTypes', false)) {
            return null;
        }

        return substr(
            serialize($value),
            0,
            config('metable.stringValueIndexLength', 255)
        );
    }

    public function isIdempotent(): bool
    {
        return true;
    }
}
