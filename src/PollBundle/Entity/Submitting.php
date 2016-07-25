<?php

namespace PollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Submitting
 *
 * @ORM\Table(name="submitting", uniqueConstraints={@ORM\UniqueConstraint(name="submitting_user", columns={"user_id", "question_id"})})
)
 * @ORM\Entity(repositoryClass="PollBundle\Repository\SubmittingRepository")
 */
class Submitting
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="value_string", type="string", length=255, nullable=true)
     */
    private $valueString;

    /**
     * @var bool
     *
     * @ORM\Column(name="value_bool", type="boolean", nullable=true)
     */
    private $valueBool;

    /**
     * @var string
     *
     * @ORM\Column(name="value_text", type="text", nullable=true)
     */
    private $valueText;

    /**
     * @var array
     *
     * @ORM\Column(name="value_array", type="array", nullable=true)
     */
    private $valueArray;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return Submitting
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set question
     *
     * @param string $question
     *
     * @return Submitting
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Submitting
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set valueString
     *
     * @param string $valueString
     *
     * @return Submitting
     */
    public function setValueString($valueString)
    {
        $this->valueString = $valueString;

        return $this;
    }

    /**
     * Get valueString
     *
     * @return string
     */
    public function getValueString()
    {
        return $this->valueString;
    }

    /**
     * Set valueBool
     *
     * @param boolean $valueBool
     *
     * @return Submitting
     */
    public function setValueBool($valueBool)
    {
        $this->valueBool = $valueBool;

        return $this;
    }

    /**
     * Get valueBool
     *
     * @return bool
     */
    public function getValueBool()
    {
        return $this->valueBool;
    }

    /**
     * Set valueText
     *
     * @param string $valueText
     *
     * @return Submitting
     */
    public function setValueText($valueText)
    {
        $this->valueText = $valueText;

        return $this;
    }

    /**
     * Get valueText
     *
     * @return string
     */
    public function getValueText()
    {
        return $this->valueText;
    }

    /**
     * Set valueArray
     *
     * @param array $valueArray
     *
     * @return Submitting
     */
    public function setValueArray($valueArray)
    {
        $this->valueArray = $valueArray;

        return $this;
    }

    /**
     * Get valueArray
     *
     * @return array
     */
    public function getValueArray()
    {
        return $this->valueArray;
    }
}
