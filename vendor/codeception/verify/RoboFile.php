<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    /**
     * Publishes new Verify release
     * @param null $newVer
     */
    public function release($newVer = null)
    {
        if ($newVer) {
            $this->say("version updated to $newVer");
            $this->taskWriteToFile(dirname(__FILE__).'/VERSION')
                ->line($newVer)
                ->run();
        }
        $version = trim(file_get_contents(dirname(__FILE__).'/VERSION'));
        $this->taskGitStack()
            ->tag($version)
            ->push('origin','master --tags')
            ->run();

        $this->taskGitHubRelease($version)
            ->uri('Codeception/Verify')
            ->askForChanges()
            ->run();
    }
}