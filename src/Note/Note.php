<?php

namespace NoteScript\Note;

use DateTime;

/**
 * An in memory representation of a note.
 */
class Note
{
    /**
     * @var string The title of the note.
     */
    private $title;

    /**
     * @var string The body content of the note.
     */
    private $content;

    /**
     * @var \DateTime The date/time the note was created.
     */
    private $date;

    /**
     * @param string $title
     * @param string $content
     * @param \DateTime $date
     */
    public function __construct($title = null, $content = null, DateTime $date = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->date = (null !== $date) ? $date : new DateTime();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
