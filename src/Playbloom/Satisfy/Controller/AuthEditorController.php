<?php

namespace Playbloom\Satisfy\Controller;

use Playbloom\Satisfy\Model\BasicAuth;
use Playbloom\Satisfy\Model\OAuth;
use Symfony\Component\HttpFoundation\Response;
use Playbloom\Satisfy\Form\Type\AuthEditorType;
use Symfony\Component\HttpFoundation\Request;
/**
 * Class AuthEditorController
 * @package Playbloom\Satisfy\Controller
 * @author  Axel Seemann <kummeraxel@gmail.com>
 *
 */
class AuthEditorController extends AbstractProtectedController
{
    /**
     * @var string
     */
    private $sshPath;

    /**
     * @var string
     */
    private $appPath;

    /**
     * @var string
     */
    private $authPath;

    /**
     * AuthEditorController constructor.
     *
     * @param string $appPath      App path
     * @param string $composerHome Path to composer home
     *
     * @return void
     */
    public function __construct(string $appPath, string $composerHome)
    {
        $this->authPath = $composerHome . "/auth.json";
        $this->appPath  = $appPath;
        $this->sshPath  = realpath($this->appPath . '/../.ssh');
    }

    /**
     * Index Action
     *
     * @param Request $request Request object
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $this->checkAccess();

        $data = [];

        foreach ($this->getAuthJson() as $key => $entries) {
            if ($key === 'http-basic') {
                foreach ($entries as $domain => $authData) {
                    $basicAuth = new BasicAuth();
                    $basicAuth->setDomain($domain)
                        ->setUsername($authData['username'])
                        ->setPassword($authData['password']);

                    $data['basicauth'][] = $basicAuth;
                }
            } else {
                foreach ($entries as $domain => $token) {
                    $oAuth = new OAuth();
                    $oAuth->setType(str_replace('-oauth', '', $key))
                        ->setDomain($domain)
                        ->setToken($token);

                    $data['oauth'][] = $oAuth;
                }
            }
        }

        $form = $this->createForm(AuthEditorType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authData = [];
            if (false === empty($_POST['auth_editor']['oauth'])) {
                foreach ($_POST['auth_editor']['oauth'] as $oauth) {
                    $authData[$oauth['type'] . '-oauth'][$oauth['domain']] = $oauth['token'];
                }
            }

            if (false === empty($_POST['auth_editor']['basicauth'])) {
                foreach ($_POST['auth_editor']['basicauth'] as $basicauth) {
                    $authData['http-basic'][$basicauth['domain']] = [
                        'username' => $basicauth['username'],
                        'password' => $basicauth['password']
                    ];
                }
            }
            $this->saveAuthJson($authData);
        }

        return $this->render(
            '@PlaybloomSatisfy/auth_editor.html.twig',
            [
                'form' => $form->createView(),
                'pubKey' => $this->getPublicKey()
            ]
        );
    }

    /**
     * Returns the ssh path
     *
     * @return string
     */
    private function getSshPath(): string
    {
        if (is_dir($this->sshPath) && is_writable($this->sshPath)) {
            return $this->sshPath;
        }

        if (false === is_dir($this->sshPath)) {
            mkdir($this->sshPath, 775, true);
        }

        if (false === is_writable($this->sshPath)) {
            chown($this->sshPath, $_ENV['APACHE_RUN_USER']);
            chgrp($this->sshPath, $_ENV['APACHE_RUN_GROUP']);
        }

        return $this->sshPath;
    }

    /**
     * Returns the public key if exists otherwise a key-pair will be created.
     *
     * @return string
     */
    private function getPublicKey(): string
    {
        $publicKey  = $this->getSshPath() . "/id_rsa.pub";
        $privateKey = $this->getSshPath() . "/id_rsa";

        if (false === file_exists($privateKey)) {
            // Generate private key
            $return = [];
            exec('ssh-keygen -t rsa -b 2048 -N "" -f /var/www/.ssh/id_rsa', $return);
        }

        if (false === file_exists($publicKey)) {
            // Generate public key
            exec('ssh-keygen -y -f /var/www/.ssh/id_rsa > /var/www/.ssh/id_rsa.pub');
        }

        return file_get_contents($publicKey);
    }

    /**
     * Returns the decoded auth json
     *
     * @return array
     */
    private function getAuthJson(): array
    {
        if (false === file_exists($this->authPath)) {
            return [];
        }

        if ($auth = json_decode(file_get_contents($this->authPath), true)) {
            return $auth;
        }

        return [];
    }

    /**
     * Save data to auth json
     *
     * @param array $auth AuthData
     *
     * @return void
     */
    private function saveAuthJson(array $auth): void
    {
        if (false === is_dir(dirname($this->authPath))) {
            mkdir(dirname($this->authPath));
        }

        if (false === file_exists($this->authPath)) {
            touch($this->authPath);
        }
        if (false === is_writable($this->authPath)) {
            chmod($this->authPath, 0664);
        }

        file_put_contents($this->authPath, json_encode($auth, JSON_PRETTY_PRINT));
    }
}