mastercoin-faucet
=================
The Mastercoin faucet is a website on which users can earn 
tiny amounts of Mastercoin, primarily to play around a little.

Project status
=================
5/5 authentication methods are implemented. Rudimentary payout
system which uses raw transactions and needs to be in sync so
that all outputs are available and balanaces are up-to-date.

To do
=================
- Replace signature based bitcointalk.org authentication with
  one-time-token string authentication  
- More autonomous and dynamic payout system
- Detect redemption of multiple rewards
- DB and code cleanup and optimization

Used compontents
=================
Bootstrap:
http://getbootstrap.com

PHP-OAuth2:
https://github.com/adoy/PHP-OAuth2

php-bitcoin-signature-routines:
https://github.com/scintill/php-bitcoin-signature-routines

phpecc:
https://github.com/mdanter/phpecc

JSON-RPC PHP:
http://jsonrpcphp.org

Contact
=================
Email:
dexx@bitwatch.co

Bitcointalk.org:
https://bitcointalk.org/index.php?action=profile;u=104899