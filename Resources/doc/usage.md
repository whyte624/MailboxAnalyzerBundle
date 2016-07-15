# Usage

When you have defined a config, you can run a console command:

``` shell
SF2: ./app/console whyte624:mailbox-analyzer:import [-vvv]
SF3: ./bin/console whyte624:mailbox-analyzer:import [-vvv]
```

This command fetches mails for last day, and analyzes them, throwing UndeliverableEvent if needed.

Or add it to your crontab, to run periodically.
