# What is this ?
[Slack](https://slack.com/) concludes by default cooperation with [Trello](https://trello.com/).  
However Slack is not the function to notify of the time limit of the card.    

### Trello --> your server --> Slack
It is necessary to prepare before time.  
You must construct an email server.  
You should be able to receive it at least.

# your server's setting

## Experiment Environment
  - OS centOS: release 6.6 (Final)

## POSTFIX(v 2.6.6)

### main.cf 
path: /etc/postfix/main.cf

`alias_maps = hash:/etx/aliases`  
`alias_database = hash:/etc/aliases`  

$ sudo service postfix restart

### aliases
path: /etc/aliases

```
# "receive user" : "command to operate when if you receive"
trello: "| /usr/bin/php /tmp/trello2slack/app.php > /dev/null 2>&1"
```

$ sudo newaliases  

## Execute Authority of trello2slack
`$ sudo chown nobody:nobody trello2slack -R`  

# Slack API
- Incoming WebHooks  
- use `curl -X POST --data-urlencode ...token=<your token>`  

# Trello
- setting -> Notifications -> (Periodically or Instantly)  
- primary email is your server's mail  
- if you want to receive time limit card, you must subscribe it  
