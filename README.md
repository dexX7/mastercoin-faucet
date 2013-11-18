mastercoin-faucet
=================
The Mastercoin faucet is a website on which users can earn 
tiny amounts of Mastercoin, primarily to play around a little.

Project status
=================
5/5 authentication methods are implemented. Payout system that
uses raw transactions and finds fitting unspent outputs. MSC 
balanace needs to be synced manually.

To do
=================
- Replace signature based bitcointalk.org authentication with
  one-time-token string authentication
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
faucet@bitwatch.co

Bitcointalk.org:
https://bitcointalk.org/index.php?action=profile;u=104899