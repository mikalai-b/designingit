<?php class Symptom extends Base\Symptom
{

    /**
     * Instantiate a new Symptom
     */
    public function __construct()
    {
        $this->dateCreated = new \DateTime();

        return parent::__construct();
    }


    /**
     *
     */
    public function __toString()
    {
        return $this->getContent();
    }
}
