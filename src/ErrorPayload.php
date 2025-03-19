<?php

namespace JMSLBAM\RayPHPErrors;

// use Spatie\Ray\Origin\DefaultOriginFactory;
use Spatie\Ray\Payloads\Payload;
use Spatie\Ray\Origin\Origin;

class ErrorPayload extends Payload
{
    /** @var string */
    protected $content;

    /** @var string */
    protected $label;

    /** @var string */
    protected $file;

    /** @var string */
    protected $lineNumber;

    public function __construct(string $content, string $fileAndLineNumber = '')
    {
        $this->content = $content;

        $this->label = 'error';

        // Sorry ;) mis-using the $label to overwrite the file and fileNumber
        $fileAndLineNumber = explode( ':', $fileAndLineNumber );

        $this->file = $fileAndLineNumber[0];
        $this->lineNumber = $fileAndLineNumber[1];
    }

    public function getType(): string
    {
        return 'custom';
    }

	public function getContent(): array
    {
        return [
            'content' => $this->content,
            'label' => $this->label,
        ];
    }

    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'content' => $this->getContent(),
            'origin' => $this->getOrigin()->toArray(),
        ];
    }

	protected function getOrigin(): Origin
    {
        /** @var \Spatie\Ray\Origin\OriginFactory $originFactory */
        $originFactory = new self::$originFactoryClass();

        $origin = $originFactory->getOrigin();

        $origin->file = $this->replaceRemotePathWithLocalPath($this->file);
        $origin->lineNumber = $this->lineNumber;

        return $origin;
    }
}
