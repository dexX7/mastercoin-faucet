mastercoin-faucet
=================
The Mastercoin faucet is a website on which users can earn 
tiny amounts of Mastercoin, primarily to play around a little.

Project status
=================
Six authentication methods are implemented (Reddit, bitcointalk.org, 
GitHub, Twitter, Facebook and Google), though the authentication via 
Facebook and Google is currently disabled due to abuse. The payout 
system uses raw transactions and finds fitting unspent outputs. MSC 
balanaces need to be synced manually the first time. Attempts
to claim multiple rewards are not allowed.

To do
=================
- Find additional strategies to combat abuse
- Code cleanup and optimization
- SEO optimization

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

tmhOAuth:
https://github.com/themattharris/tmhOAuth

Contact
=================
Email:
faucet@bitwatch.co

Bitcointalk.org:
https://bitcointalk.org/index.php?action=profile;u=104899