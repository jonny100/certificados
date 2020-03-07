<?php

namespace App\Entity;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="certificados_file")
 */
class CertificadosFile
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
   
    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $name;
   
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;
   
    /**
     * @ORM\Column(type="string", length=45)
     */
    protected $mimetype;
   
    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;
   
    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;
    
    /**
     * @var int|null
     *
     * @ORM\Column(name="estado", type="integer", nullable=true, options={"default"="1"})
     */
    private $estado = '1';
   
    #
    # Not persistent properties
    #
    protected static $basepath = null;
   
    #
    # Methods
    #
    public function __construct() {
        $this->created_at = new \Datetime();
    }
   
    public function __toString() {
        return 'Certificado:'.$this->getId();
    }
   
    static public function setBasePath($dir) {
        self::$basepath = realpath($dir);
    }
   
    static public function getBasePath() {
        if (self::$basepath === null) {
            throw new \RuntimeException("Trying to access upload directory for profile files");
        }
        return self::$basepath;
    }
    public function getAbsolutePath() {
        return self::getBasePath() . DIRECTORY_SEPARATOR . $this->path;
    }
    public function getFullPath() {
        return $this->getAbsolutePath() . DIRECTORY_SEPARATOR . $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getMimetype(): ?string
    {
        return $this->mimetype;
    }

    public function setMimetype(string $mimetype): self
    {
        $this->mimetype = $mimetype;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(?int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }
    
    
    
}