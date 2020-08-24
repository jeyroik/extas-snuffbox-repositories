<?php
namespace extas\components\repositories;

use extas\components\extensions\Extension;
use extas\components\extensions\ExtensionRepository;
use extas\components\extensions\ExtensionRepositoryDescription;
use extas\components\plugins\PluginRepository;
use extas\interfaces\extensions\IExtensionRepositoryDescription;
use extas\interfaces\repositories\IRepositoryDescription;

/**
 * Trait TSnuffRepositoryDynamic
 *
 * @package extas\components\repositories
 * @author jeyroik <jeyroik@gmail.com>
 */
trait TSnuffRepositoryDynamic
{
    use TSnuffRepository;

    /**
     * @param array $repositoriesConfigs
     * @throws \Exception
     */
    public function createSnuffDynamicRepositories(array $repositoriesConfigs)
    {
        $this->registerSnuffRepos([
            'extensionRepository' => ExtensionRepository::class,
            'pluginRepository' => PluginRepository::class,
            'repositories' => RepositoryDescriptionRepository::class
        ]);

        $this->addSnuffDynamicRepositories($repositoriesConfigs);
    }

    /**
     * @param array $repositoriesConfigs
     * @throws \Exception
     */
    public function addSnuffDynamicRepositories(array $repositoriesConfigs)
    {
        foreach ($repositoriesConfigs as $repository) {
            list($name, $pk, $class) = $repository;
            $this->createWithSnuffRepo('extensionRepository', new Extension([
                Extension::FIELD__CLASS => ExtensionRepositoryDescription::class,
                Extension::FIELD__INTERFACE => IExtensionRepositoryDescription::class,
                Extension::FIELD__SUBJECT => '*',
                Extension::FIELD__METHODS => [$name]
            ]));

            $this->createWithSnuffRepo('repositories', new RepositoryDescription([
                RepositoryDescription::FIELD__NAME => $name,
                RepositoryDescription::FIELD__SCOPE => 'extas',
                RepositoryDescription::FIELD__PRIMARY_KEY => $pk,
                RepositoryDescription::FIELD__CLASS => $class,
                RepositoryDescription::FIELD__ALIASES => [$name]
            ]));
        }
    }

    /**
     * @throws \extas\components\exceptions\MissedOrUnknown
     */
    public function deleteSnuffDynamicRepositories()
    {
        /**
         * @var IRepositoryDescription[] $repos
         */
        $repos = $this->allSnuffRepos('repositories');
        foreach ($repos as $repo) {
            $tmp = new RepositoryDynamic([
                RepositoryDynamic::FIELD__REPOSITORY_DESCRIPTION => $repo
            ]);
            $tmp->drop();
        }

        $this->unregisterSnuffRepos();
    }
}
