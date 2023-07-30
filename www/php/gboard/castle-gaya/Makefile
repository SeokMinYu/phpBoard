# by neodal #
PHP_PACKAGE	= castle-php
PHP_VERSION	= 1.0.2

SVN_DIR  = /home/castle_svn/
SVN_PACKAGE_DIR = /home/castle_svn/packages

PHP_SOURCES := img/ log/ Readme.txt index.htm index.html default.htm install.php install_step1.php \
			   install_step2.php install_step2_submit.php style.css castle_admin.php \
			   castle_admin_account.php castle_admin_account_submit.php castle_admin_advance.php \
			   castle_admin_advance_detail.php castle_admin_advance_submit.php castle_admin_backup.php \
			   castle_admin_bottom.php castle_admin_config.php castle_admin_config_submit.php \
			   castle_admin_download.php castle_admin_lib.php castle_admin_log.php castle_admin_log_submit.php \
			   castle_admin_login.php castle_admin_login_submit.php castle_admin_logout_submit.php \
			   castle_admin_menu.php castle_admin_policy.php castle_admin_policy_submit.php \
			   castle_admin_policy_view.php castle_admin_title.php castle_admin_top.php castle_referee.php castle_referee_raw.php castle_version.php

all: version_castle-php package_castle-php 

package_castle-php: 
	@echo -n "* Packaging CASTLE PHP Version ...                      "
	@mkdir $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)/ 
	@cp -rf $(PHP_SOURCES) $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)/ 
	@rm -rf $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)/.svn
	@rm -rf $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)/img/.svn
	@rm -rf $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)/img/*.tmp
	@rm -rf $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)/log/.svn
	@rm -rf $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)/log/*.txt
	@tar -C $(SVN_PACKAGE_DIR) -zcf $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)-$(PHP_VERSION).tar.gz $(PHP_PACKAGE)/ --overwrite
	@tar -C $(SVN_PACKAGE_DIR) -jcf $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)-$(PHP_VERSION).tar.bz2 $(PHP_PACKAGE)/ --overwrite
	@(cd $(SVN_PACKAGE_DIR); zip -q -r $(PHP_PACKAGE)-$(PHP_VERSION).zip $(PHP_PACKAGE))
	@rm -rf $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)
	@svn add $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)-$(PHP_VERSION).zip --quiet 2> /dev/null
	@svn add $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)-$(PHP_VERSION).tar.gz --quiet 2> /dev/null
	@svn add $(SVN_PACKAGE_DIR)/$(PHP_PACKAGE)-$(PHP_VERSION).tar.bz2 --quiet 2> /dev/null
	@echo "[[1;36mDone[0;37m]"

version_castle-php:
	@echo -n "* Creating CASTLE PHP Version ...                       "
	@echo "<?php" > castle_version.php
	@echo "define(\"CASTLE_VERSION\", \"$(PHP_VERSION)\");" >> castle_version.php
	@echo "?>" >> castle_version.php
	@echo "[[1;36mDone[0;37m]"

