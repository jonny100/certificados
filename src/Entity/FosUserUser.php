<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FosUserUser
 *
 * @ORM\Table(name="fos_user_user", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_C560D761C05FB297", columns={"confirmation_token"}), @ORM\UniqueConstraint(name="UNIQ_C560D761A0D96FBF", columns={"email_canonical"}), @ORM\UniqueConstraint(name="UNIQ_C560D76192FC23A8", columns={"username_canonical"})})
 * @ORM\Entity
 */
class FosUserUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=180, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="username_canonical", type="string", length=180, nullable=false)
     */
    private $usernameCanonical;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=180, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email_canonical", type="string", length=180, nullable=false)
     */
    private $emailCanonical;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;

    /**
     * @var string|null
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $salt = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true, options={"default"="NULL"})
     */
    private $lastLogin = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="confirmation_token", type="string", length=180, nullable=true, options={"default"="NULL"})
     */
    private $confirmationToken = 'NULL';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="password_requested_at", type="datetime", nullable=true, options={"default"="NULL"})
     */
    private $passwordRequestedAt = 'NULL';

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array", length=0, nullable=false)
     */
    private $roles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_of_birth", type="datetime", nullable=true, options={"default"="NULL"})
     */
    private $dateOfBirth = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="firstname", type="string", length=64, nullable=true, options={"default"="NULL"})
     */
    private $firstname = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="lastname", type="string", length=64, nullable=true, options={"default"="NULL"})
     */
    private $lastname = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="website", type="string", length=64, nullable=true, options={"default"="NULL"})
     */
    private $website = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="biography", type="string", length=1000, nullable=true, options={"default"="NULL"})
     */
    private $biography = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=true, options={"default"="NULL"})
     */
    private $gender = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="locale", type="string", length=8, nullable=true, options={"default"="NULL"})
     */
    private $locale = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="timezone", type="string", length=64, nullable=true, options={"default"="NULL"})
     */
    private $timezone = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone", type="string", length=64, nullable=true, options={"default"="NULL"})
     */
    private $phone = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="facebook_uid", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $facebookUid = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="facebook_name", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $facebookName = 'NULL';

    /**
     * @var json|null
     *
     * @ORM\Column(name="facebook_data", type="json", nullable=true, options={"default"="NULL"})
     */
    private $facebookData = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="twitter_uid", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $twitterUid = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="twitter_name", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $twitterName = 'NULL';

    /**
     * @var json|null
     *
     * @ORM\Column(name="twitter_data", type="json", nullable=true, options={"default"="NULL"})
     */
    private $twitterData = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="gplus_uid", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $gplusUid = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="gplus_name", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $gplusName = 'NULL';

    /**
     * @var json|null
     *
     * @ORM\Column(name="gplus_data", type="json", nullable=true, options={"default"="NULL"})
     */
    private $gplusData = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $token = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="two_step_code", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $twoStepCode = 'NULL';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="FosUserGroup", inversedBy="user")
     * @ORM\JoinTable(name="fos_user_user_group",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *   }
     * )
     */
    private $group;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->group = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
