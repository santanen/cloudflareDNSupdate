# cloudflareDNSupdate
Server side PHP script to update Cloudflare DNS A records for a set of domains. The records are only updated if public IP has changed.

Requires: **php5-cli, php5-curl**

## Usage

Copy config.php.sample to `config.php` and enter your

* cloudflare token
* cloudflare email address
* domain names, which need to be updated

Run `php cloudflare.php` and see what happens :)

First run of the scripts creates a file `ipfile.txt` with the public IP. It is used to check if the public IP has changed between runs. If yes, start looping through your domains and update the A record if needed.

## Run as cronjob

You may run the script directly from crontab. Example entry:

`*/10 * * * * php [your path goes here]/git/cloudflareDNSupdate/cloudflare.php`

**NOTE**: the script writes to STDOUT and STDERR only when the public IP has changed or an error occurs. So, you should not get and inbox full of cronjob messages with this.

