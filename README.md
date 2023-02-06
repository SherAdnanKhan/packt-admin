# Packt New Admin Portal

## Background

Following are the versions of frameworks, libraries and tools used in this project:

- Laravel: ^7.x
- Composer: 1.10.7
- Node: ^12.0
- PHP: 7.4

## Running Project

### Step 1: env files

There are 2 env files which need to be created for this project:

- Project env file (for laravel):
`./.env`

- Database env file (for the local docker instance):
`./docker-custom/env/.docker-db`

**Note: For every env file there is a corresponding `{filename}.example` present in same directory as env file containing sample values.**


### Step 2: Running Docker compose

Simple. Nothing complicated here.

```bash
docker-compose up -d --build
```

### Step 3: After build script

The following script is making after build changes:
* run composer install
* fix permissions
* fix user group
* generate token and clear cache

```bash
 sh scripts/after-build.sh
```

**Note: If you are working on this application, execute commands for this step in a new terminal.**

### Step 4: Open browser

Navigate to `http://localhost`

### Step 5: Node Dependencies

Assuming we don't have a Node development environment, use following scripts to start react application bundling / node_modules installation:

| NPM | Script |
|--|--|
|npm run watch | `sh scripts/npm-watch.sh`|
|npm run watch-poll | `sh scripts/npm-watch-poll.sh`|
|npm run prod | `npm run prod`|
|npm install | `sh scripts/npmi.sh [package_name] [options: '--save-dev', '--save', '-g']`|
| | example: `sh scripts/npmi.sh axios -g`|

# Access to PgAdmin Database GUI

* **URL:** `http://localhost:5050`
* **Username:** pgadmin4@pgadmin.org (as a default)
* **Password:** admin (as a default)

# Development Workflow
Follow the GitFlow workflow as detailed below.  

## Development
1. Checkout the develop branch  
    ```git checkout develop```

2. Create a Feature branch from develop. Work happens on feature branch  
    ```git checkout -b branch_name ```

3. When a feature is complete, merge into develop. PR approvals required  
Create a PR on github to merge  *"branch_name"* into develop. After PR approvals merge using github or with the git commands below.  
    ```git checkout develop   ```   
    ```git merge branch_name ```  

4. To deploy a feature into staging environment  
    ```git checkout staging```  
    ```git merge branch_name ```  

5. To release a new version to live.   
    ***Start a release branch***  
    ```git checkout master```  
    ```git checkout -b release/0.1.0```  
    ```git merge branch_name ```  
      
    ***Finish a release branch.*** PR approvals required  
    Create a PR on github to merge *"release/0.1.0"* into master. After PR approvals merge with github or with the git commands below.  
    ```git checkout master```  
    ```git merge release/0.1.0```    
      
    ***Merge the release branch into develop and staging***  
    ```git checkout develop```   
    ```git merge release/0.1.0```     
    ```git checkout staging```  
    ```git merge release/0.1.0```   

## Hot-fixes 
If an issue in master is detected:
- A hotfix branch is created from master  
- Once the hotfix is complete it is merged to both develop and master

For details please feel free to visit https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow

# **Branch Naming**
**Branch Naming**  
Following a branch naming scheme provides semantic versioning and a clean root repository where branches are neatly organised by type and more easily found.

Branches **MUST** be prefixed with one of the following prefix types:
	
- **feature/** - This is for new features being developed  
- **bug/** - This is for defects  
- **poc/** - This is for proof of concepts that may or may not be turned into features  
- **misc/** - This is for anything that doesn't fall into the above, such as reverting accidental commits.  If you find something consistently doesn't fall into the above, report to your team lead.  	
- **release/** - This is for release candidates and release branches    
- **temp/** - This is for temporary branches to enable the ability to deploy multiple branches together to a test environment.  Please delete these branches when they are no longer required.   
      
***For features, bugs or proof of concepts:***   
	1. after the prefix, the branch name **MUST** state the JIRA ticket number followed by a meaningful description of the ticket  
	2. Branch names **MUST NOT** contain the developer's name.  
	3. The description **MUST** be lower case    

***Below is the format:***  

```{prefix}/{ticket-number}-{ticket-description-no-spaces}```   

example: 

```feature/PAC-2045-some-future-feature```  

For miscellaneous branches, the branch name MUST state at least a meaningful description of what this branch is for. If a JIRA ticket has been opened for this, it SHOULD be provided in the same format as above.



# **Commit Messages**

Git commit messages MUST be prefixed with the JIRA ticket number followed by a meaningful description of the change.

``` 
{ticket-number} {type}[optional scope]:{description}
[optional body]
[optional footer(s)]
```    
### **Simple commit message Examples**  ###  
```PAC-2046 feat: add CTA in chapter block```  
```PAC-2047 fix: fix navigation margin```  
```PAC-2082 docs: updating CHANGELOG by adding features in the new release```   
```PAC-2013 chore: correct minor typos in code```  
```PAC-2024 hot-fix: update correct version number for lambda nodejs```  
  
***with scope***  
```PAC-2094 feat(parser): add ability to parse arrays```  
```PAC-2094 fix(style): fix reponsovines of footer on mobile screen size ```
  
**with a footer**  
```
PAC-2045 feat: allow provided config object to extend other configs  
  
BREAKING CHANGE: `extends` key in config file is now used for extending other config files  
```

type could be feat:, fix, hot-fix, build:, chore:, ci:, docs:, style:, refactor:, perf:, test:, and others.



**Bad example - not acceptable**  
PAC-0000 Remove validation from tel num on checkout  
PAC-0001 Remove enable/disable from order status.

