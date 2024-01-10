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

```
There is also a deployment **shell script** is to be used in QA/Prod environent. You may need to customise some of the tasks within the shell script, for example to the `$ ./artisan down` can accept other arguments to allow access by certain IP address or using a bypass token.

```
# run the procedural tasks to deploy
$ ./deploy.sh
```

## Roadmap

The following are under consideration for future additions to this application.

- Connection to websocket server for example - `wss://localhost:8989`
- Websocket server is bi-directional comms channel, feeding data to frontend as and when data changes or becomes available.
- Websocket to be built in Node.js or PHP through [RachetPHP](http://socketo.me/), [Swoole](https://openswoole.com/), etc.
- OHLCV (**O**pen, **H**igh, **L**ow, **C**lose, **V**olume) data is captured from leading Exchange platforms such as **Binance**, **Kraken**, **Coinbase** with consolidation and stored in InfluxDB or MongoDB
- Time-Series data needs to be structured and optimised well for high capacity storage in `{JSON}` format. 
- For production use case only if available to public (www), proxy the request to port mapping for example `wss://www.example.com:8989`
- Allow traders to download their trading history by connecting their exchange platform and sync'ing their trading data in XLS format.
- Allow traders to create rules for the trading bots (DCA - Dollar-Cost-Average).
- Process that runs on node [x] / EC2, process control `PCNTL` through `supervisord`.
- Add TA (**Technical Analysis**) modules for further analysis on the coins using BB, MACD, RSI, etc.
- Auth0 authentication for SSO between other apps, to handle blacklisting and Dos/DDos mitigation, API authentication and more.
- Deployer for deploying the app. [Laravel Deployer](https://deployer.org/docs/7.x/recipe/laravel) or [Laravel Forge](https://forge.laravel.com/)
- To separate shared libraries to repo `coinhoppa.lib` and to enforce `\\Coinhoppa\\` namespace. Allowing us to easily add SDK packages. 
- To add additional repositiories to existing you will need to add the `repositories:{}` element to the composer.json file [Repositories](https://getcomposer.org/doc/05-repositories.md)
- To embrace TALL stack (Tailwind, Alphine, ...)
- Use of Telescope for optimisation and debugging must only be on dev local or QA.
- To build frontend stack as SPA (Single Page Application), and allow connections to APIs to power all the bells & whistles. [JWT](https://jwt.io/) may end up in the mix here, also using the browsers localStorage && sessionStorage APIs.
- FCP/LCP - PageSpeed, Network timing measurement through sending beacons. Check browser support for `PING` type request.
- DB Migrations/Schema changes to run through repo, naming conventions to include JIRA ticket, or JIRA to link developer branch to ticket as part of the workflow.
- Strict following of Gitflow Workflow is a must! (branches like `develop`, `feature/CH-103478-add-aws-trace-id`. You must branch of the `develop` as the main branch is the squash + merge :D)
- Production DB changes to be verified by DBA and signed-off, any downtimes to be scheduled in advance.
- i18n changes -> DB strict utf-8 character encoding support, localization, translations, currency exchange rates, and more.
- TBC

## Contributing

No contributions are considered at present moment. If you have any questions reach out to [Rahel Chowdhury](mailto:rahelahmed79@gmail.com)

## License

[MIT](https://choosealicense.com/licenses/mit/)
