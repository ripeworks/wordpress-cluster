# wordpress-cluster
Run a scalable, stateless cluster of Wordpress sites on AWS

![](docs/infra.svg)

## Idea

Use Kubernetes as a container platform to run tiny stateless instances of Wordpress. Each container uses EFS for file storage, and RDS for database storage.

## Structure

This setup is intended for running multiple separate Wordpress websites. A lot of setups I see are for setting up a cluster for running only one website.

### Cloudfront

The entry point of any user request will be to a CloudFront distribution. The distribution should be tied to the main domain name using CNAMEs as well as have a valid SSL cert attached (ACM). For each different Wordpress you have, set up a distribution. All of the distributions will route to the single ELB instance. The traffic to the ELB is done over SSL, so make sure you have a certificate attached to your ELB, and that the cloudfront distributions point to that domain name.

### LoadBalancer

The Kubernetes cluster has one main LoadBalancer in the form of an ELB. Each request coming into the ELB will be passed to the individual nodes.

### Proxy

All requests from the ELB are sent into an nginx proxy deployment which will route the request to the appropriate Wordpress deployment usually based on the hostname of the request.

### Container

Each Wordpress site is served by one or more containers. Each container is a basic Alpine linux image with php7, php-fpm, and nginx. The containers will volume mount to EFS at the specified location where its Wordpress root is.

## Setup

1. Setup your Kubernetes cluster using KOPS
2. Create an EFS.
3. `ssh` into a kubernetes node, mount your EFS, and set up your folder structure. This repo uses `/wordpress/<example.com>/` as its structure for each website.  
```
/
└── wordpress
    └──  example.com
         ├── logs
         ├── sessions
         ├── wordpress # Wordpress root
         └── wp-config.php
```
__Note:__ Containers will volume mount `/wordpress/mywebsite` from the EFS to `/app`

4. Create a MySQL compatible RDS cluster and set up your database(s).
5. Deploy `proxy-deployment.yml` to set up your nginx proxy and ELB.
6. Set up each deployment using `wordpress-deployment.yml` as a template. Deploy as many for as many sites as you want.  
