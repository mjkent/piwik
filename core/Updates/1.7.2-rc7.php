<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Updates;

use Piwik\Common;
use Piwik\Db;
use Piwik\Updater;
use Piwik\Updates;

/**
 */
class Updates_1_7_2_rc7 extends Updates
{
    static function getSql($schema = 'Myisam')
    {
        return array(
            'ALTER TABLE `' . Common::prefixTable('user_dashboard') . '`
		        ADD `name` VARCHAR( 100 ) NULL DEFAULT NULL AFTER  `iddashboard`' => false,
        );
    }

    static function update()
    {
        try {
            $dashboards = Db::fetchAll('SELECT * FROM `' . Common::prefixTable('user_dashboard') . '`');
            foreach ($dashboards AS $dashboard) {
                $idDashboard = $dashboard['iddashboard'];
                $login = $dashboard['login'];
                $layout = $dashboard['layout'];
                $layout = html_entity_decode($layout);
                $layout = str_replace("\\\"", "\"", $layout);
                Db::query('UPDATE `' . Common::prefixTable('user_dashboard') . '` SET layout = ? WHERE iddashboard = ? AND login = ?', array($layout, $idDashboard, $login));
            }
            Updater::updateDatabase(__FILE__, self::getSql());
        } catch (\Exception $e) {
        }
    }
}
