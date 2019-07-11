<?php
/*********************************************************************
    audits.php

    Audit Logs

    Adriane Alexander
    Copyright (c)  2006-2019 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('admin.inc.php');

if (PluginManager::auditPlugin())
    require_once(sprintf('phar:///%s/plugins/audit.phar/class.audit.php', INCLUDE_DIR));

switch (strtolower($_REQUEST['t'])) {
  case 'audits':
      if (PluginManager::auditPlugin() && $_REQUEST['a'] == 'export') {
         foreach (AuditEntry::getTypes() as $abbrev => $info) {
             if ($_REQUEST['type'] == $abbrev)
                $name = AuditEntry::getObjectName($info[0]);
         }
          $filename = sprintf('%s-audits-%s.csv',
                  $name, strftime('%Y%m%d'));

          if (!Export::audits('audit', $filename))
              $errors['err'] = __('Unable to dump query results.')
                  .' '.__('Internal error occurred');
      }
      break;
}

$page= sprintf('phar:///%s/plugins/audit.phar/templates/auditlogs.tmpl.php', INCLUDE_DIR);
$nav->setTabActive('dashboard');
$ost->addExtraHeader('<meta name="tip-namespace" content="dashboard.audit_logs" />',
    "$('#content').data('tipNamespace', 'dashboard.audit_logs');");
require(STAFFINC_DIR.'header.inc.php');
require($page);
include(STAFFINC_DIR.'footer.inc.php');
?>
