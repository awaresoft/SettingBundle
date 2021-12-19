<?php

namespace Awaresoft\SettingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="setting__setting", indexes={
 *     @ORM\Index(name="idx_name", columns={"name"}),
 *     @ORM\Index(name="idx_hidden", columns={"hidden"})
 * })
 *
 * @ORM\Entity(repositoryClass="Awaresoft\SettingBundle\Entity\Repository\SettingRepository")
 *
 * @author Bartosz Malec <b.malec@awaresoft.pl>
 */
class Setting
{
    const DEFAULT_DELETABLE = true;
    const DEFAULT_HIDDEN = false;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $info;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $enabled;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $deletable;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $hidden;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $runMethod;

    /**
     * @ORM\OneToMany(targetEntity="Awaresoft\SettingBundle\Entity\SettingHasField", mappedBy="setting", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="setting_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var SettingHasField[]|ArrayCollection
     */
    protected $fields;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
        $this->enabled = true;
        $this->hidden = self::DEFAULT_HIDDEN;
        $this->deletable = self::DEFAULT_DELETABLE;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param string $info
     *
     * @return $this
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        return $this->deletable;
    }

    /**
     * @param bool $deletable
     *
     * @return $this
     */
    public function setDeletable($deletable)
    {
        $this->deletable = $deletable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     *
     * @return $this
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @return SettingHasField[]|ArrayCollection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param SettingHasField[] $fields
     *
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fields = new ArrayCollection();

        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this;
    }

    /**
     * @param SettingHasField $field
     */
    public function addField(SettingHasField $field)
    {
        $field->setSetting($this);
        $this->fields[] = $field;
    }

    /**
     * @return string
     */
    public function getRunMethod()
    {
        return $this->runMethod;
    }

    /**
     * @param string $runMethod
     *
     * @return Setting
     */
    public function setRunMethod($runMethod)
    {
        $this->runMethod = $runMethod;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (!$this->name) {
            return '';
        }

        return $this->getName();
    }
}
