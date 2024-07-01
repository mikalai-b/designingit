<?php

/**
 *
 */
class Answer extends Base\Answer
{
    /**
     * Instantiate a new Answer
     */
    public function __construct()
    {
        $this->dateCreated = new \DateTime();

        return parent::__construct();
    }


    /**
     *
     */
    public function getKey()
    {
        return md5($this->question);
    }


	/**
	 * Get the value of content
	 *
	 * @access public
	 * @return array The value of content
	 */
	public function getContentAsArray()
	{
        if (!is_array($this->content)) {

            return $this->content ? unserialize($this->content) : [];
        }
        return $this->content;
	}


	/**
	 * Set the value of content
	 *
	 * @access public
	 * @param array|string $value The value to set to content
	 * @return Answer The object instance for method chaining
	 */
	public function setContent($value)
	{
        if (is_array($value)) {
            return parent::setContent(serialize($value));
        }
        return parent::setContent($value);
	}
}
