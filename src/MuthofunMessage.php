<?php

namespace Sikhlana\MuthofunSmsChannel;

class MuthofunMessage
{
    /**
     * The message content.
     *
     * @var  string
     */
    private $content;

    /**
     * Individual lines for the content to be built.
     *
     * @var  array
     */
    private $lines = [];

    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $from;

    /**
     * True if the message should be parsed as unicode.
     *
     * @var bool
     */
    public $unicode = false;

    /**
     * Create a new message instance.
     *
     * @param  string $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the phone number the message should be sent from.
     *
     * @param  string  $from
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Add a line of text to the notification.
     *
     * @param string $line
     * @return $this
     */
    public function line($line = '')
    {
        $this->lines[] = $line;

        return $this;
    }

    /**
     * Builds the message to a string.
     *
     * @return string
     */
    public function buildMessage()
    {
        if (! empty($this->lines)) {
            if (! empty($this->content)) {
                $this->content .= "\r\n";
            }

            $this->content .= implode("\r\n", $this->lines);
        }

        return $this->content;
    }

    /**
     * Set the message content.
     *
     * @param  string $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Sets the message encoding to unicode.
     *
     * @param  bool $unicode
     * @return $this
     */
    public function unicode($unicode = true)
    {
        $this->unicode = $unicode;

        return $this;
    }
}