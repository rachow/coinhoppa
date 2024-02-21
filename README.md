# Coinhoppa 🤖 🚀 

**Coinhoppa** is an experimental project to gain exposure to the blockchain technology and cryptocurrency industry. 

The project will provide a good foundation on building a SaaS product to the martket! 

The project is on-going with changes added as seen feasible.

<img width="474" alt="coinhoppa-logo" src="https://github.com/rachow/coinhoppa/assets/12745192/cd2e02f9-554f-4d9f-bc50-d2de5e8bfd07">


## Installation

You can copy and download this repository. Here is an example.

```bash
git clone https://github.com/rachow/coinhoppa.git
```

## Usage
This project is based on the Laravel framework, therefore you should be comfortable with the framework itself before proceeding. 

If you have not installed composer globally then you will need to get composer and node package manager running.

```shell
$ composer install
$ npm install
$ npm run dev
$ npm run build
$ php artisan serve
  INFO  Server running on [http://127.0.0.1:8000].
```
There is also a deployment **shell script** is to be used in QA/Prod environment. You may need to customise some of the tasks within the shell script, for example to the `$ ./artisan down` can accept other arguments to allow access by certain IP address or using a bypass token.

Also note that the down argument can accept a `--retry=60` that will force the View Object to return HTML which includes the meta refresh tag. The meta refresh tag allows us to ensure that the page is reloaded in set interval in situations when we are certain that the platform will be brought back up in [x] seconds or forces the users session to reload without the user taking an action. 

```
# run the procedural tasks to deploy
$ ./deploy.sh
```

## Diagrams
Some initial diagrams to illustrate the platform in parts.

![Coinhoppa-Architecture-Shared-Library](https://github.com/rachow/coinhoppa/assets/12745192/20f3eef1-0580-4a4c-ab26-8b8c1febbe12)

Tree Snapshot of the shared library in progress.

![image](https://github.com/rachow/coinhoppa/assets/12745192/6d338d36-1288-4e1f-b8d0-8bfd4344e608)


## Roadmap / TLD;R

The following are under consideration for future additions to this application.

- Connection to websocket server for example - `wss://localhost:8989`. Aha moment is that frontend SPAs will tinker with ://coinhoppa.com:8989 ...
- Internal Hosts mapping can consist where `WS://` is the websocket connection. For external connection the protocol `WSS://` must be used for a secure connection.
- Websocket server is bi-directional comms channel, feeding data to frontend as and when data changes or becomes available.
- Websocket to be built in Node.js or PHP through [RachetPHP](http://socketo.me/), [Swoole](https://openswoole.com/), etc.
- OHLCV (**O**pen, **H**igh, **L**ow, **C**lose, **V**olume) data is captured from leading Exchange platforms such as **Binance**, **Kraken**, **Coinbase** with consolidation and stored in InfluxDB or MongoDB.
- Time-Series data needs to be structured and optimised well for high capacity storage in `{JSON}` format. 
- For production use case only if available to public (www), proxy the request to port mapping for example `wss://www.example.com:8989`.
- Allow traders to download their trading history by connecting their exchange platform and sync'ing their trading data in XLS format.
- Allow traders to create rules for the trading bots (DCA - Dollar-Cost-Average).
- Process that runs on node [x]-[x]-[x] / EC2, process control `PCNTL` through `supervisord`. This allows us to ensure that the worker process is always running.
- Add TA (**Technical Analysis**) modules for further analysis on the coins using BB, MACD, RSI, etc.
- Auth0 authentication for SSO between other apps, to handle blacklisting (IPV4/IPV6) and Dos/DDos mitigation, API authentication and more. Amazon also play important role, stick a Gateway API, WAF, Firewall.
- Deployer for deploying the app. [Laravel Deployer](https://deployer.org/docs/7.x/recipe/laravel) or [Laravel Forge](https://forge.laravel.com/).
- To separate shared libraries to repo `coinhoppa.lib` and to enforce `\\Coinhoppa\\` namespace. Allowing us to easily add SDK packages including following PSR-0 standards. 
- To add additional repositiories to existing you will need to add the `repositories:{}` element to the composer.json file [Repositories](https://getcomposer.org/doc/05-repositories.md).
- [Coinhoppa Lib](https://github.com/rachow/coinhoppa.lib/tree/develop)
- To embrace TALL stack (Tailwind, Alphine, ...).
- Use of Telescope for optimisation and debugging must only be on dev local or QA.
- To build frontend stack as SPA (Single Page Application), and allow connections to APIs to power all the bells & whistles. [JWT](https://jwt.io/) may end up in the mix here, also using the browsers localStorage && sessionStorage APIs.
- FCP/LCP - PageSpeed, Network timing measurement through sending beacons. Check browser support for `PING` type request.
- DB Migrations/Schema changes to run through repo, naming conventions to include JIRA ticket, or JIRA to link developer branch to ticket as part of the workflow.
- Strict following of Gitflow Workflow is a must! (branches like `develop`, `feature/CH-103478-add-aws-trace-id`. You must branch of the `develop` as the main branch is the squash + merge :D).
- Peer code reviews to take place, Coding Standards to be documented on Confluence.
- Follow Coding Standards (PSR) along with a focus on Principles by [OWASP](https://owasp.org/).
- Production DB changes to be verified by DBAs and signed-off, any downtimes to be scheduled in advance.
- i18n changes -> DB strict utf-8 `utf8_general_ci` character encoding support, localization, translations, currency exchange rates, and more.
- Kline service to pump out Candlestick data from external platforms?? `kline.example.com/symbols/btc`
- API to follow REST standards, don't build APIs that you will hate, follow principles - [JSON API](https://jsonapi.org/)
- Still shipping with MVC? - [Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- To create GitHub Workflows `prod.yaml` && `alpha.yaml` files to trigger CI/CD deployment. This means that the `deploy.sh` may not be required.
- Monitor SQLs - QPS (Query Per Second) and TPS (Transaction Per Second), Turn on the MySQL Slow Log Query on QA/Alpha if needed.
- Database Security Principles - Love it or hate it!
  - Monitor MySQL User Activities and create scripts/procs that alerts the Top Dogs ⏰.
  - Revoke user and app MySQL user priviledges every so often, so avoid credentials being leaked and threats approaching.
  - Always pay attention to the type of access provided to the developer/user, especially in a production DB instance.
    - Ask yourself, do they know what they are doing?
    - Do they have the expert SQL ninja skills to battle the big beast?
    - What operations will they perform, to what? Is it convenient timing? will it create a hiccup on the platform?
    - Ensure that DBAs are held accountable.
  - Always provide READ-ONLY access to production instances, hold only limited individuals accountable.
  - Avoid the `%` user host when adding or editing user accounts, always try to be specific in the IP address, or ensure there are security policies in place (AWS).
  - Schedule and Off-load intensive SQLs, ETLs, Reporting to a timely off-peak period.
  - DB Backup is essential, daily, nightly backups. The Golden rule is usually to replicate 3 copies, 2 copies remain on-site and the last off-site. This is initiated from a Disaster Recovery Plan.
- Time to scale the DB?
  - Horizontal Scaling [x]->[x], which is where Master/Slave(s) instances exist. There is a couple of things that come to mind.
    - Identify the read heavy part of the applications. SELECT... any data in cache like Redis or Memcached?
    - Read replicas will always be behind Write replicas, so what is the latency? How does this affect the data and in what way?
  - DB Sharding, this is where you go back to the drawing board like the Normalization step, You will define how you will split a massive table data into split tables, but here is the tricky part, you will need to know depending on the relational integrity how the applications will fetch the data and from which sharded table?
  - You could use the primary KEY and write a stub that will determine the table based on the AUTO-INCREMENTAL field or you will create pivot tables to hold sharding lookup reference.
- Introduce backend platform components in GO for low level interaction within the OSI model.
  - Concurrency, Scalability, Performance.
  - Network level (OSI) language that allows us to work with TCP/IP fast.
  - Parallel processing, multiple eXchange comms 
- Advanced Bot/Algorithm calcs using Python.
    - **NumPy** Open Source Library (Advanced Quantative Analysis and Scientitic calculation).
    - **Pandas** Open Source Library (Data Analysis and Manipulation, visualize data from OHLCV)
    - **TensorFlow** TF Library used for Machine Learning (ML) and Artificial Intelligence (AI) for trading bots.
    - **TA-Lib** Open Source project [TA-Lib](https://ta-lib.org/) and Python Libarary for analyzing data related to indicators such as, MACD, BB, RSI and more. 
- 2FA and MFA Authentication mechanisms for Traders and Admins where possible.
- TBC.

## Contributing

No contributions are considered at present moment. If you have any questions reach out to [Rahel Chowdhury](mailto:rahelahmed79@gmail.com)

## License

[MIT](https://choosealicense.com/licenses/mit/)
