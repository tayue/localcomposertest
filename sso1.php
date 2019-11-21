<?php
/**
php -S http://local.test.com/ssodemo/server -t ssodemo/server/
export SSO_SERVER=http://local.test.com/ssodemo/server SSO_BROKER_ID=Alice SSO_BROKER_SECRET=8iwzik1bwd; php -S 192.168.99.88:9001 -t ssodemo/broker/
export SSO_SERVER=http://local.test.com/ssodemo/server SSO_BROKER_ID=Greg SSO_BROKER_SECRET=7pypoox2pc; php -S 192.168.99.88:9002 -t ssodemo/broker/
export SSO_SERVER=http://local.test.com/ssodemo/server SSO_BROKER_ID=Julias SSO_BROKER_SECRET=ceda63kmhp; php -S 192.168.99.88:9003 -t ssodemo/ajax-broker/
 *   
 */
