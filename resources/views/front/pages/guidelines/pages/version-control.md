## Branches

If you're going to remember one thing in this guide, remember this: **Once a project has gone live, the master branch must always be stable**. It should be safe to deploy the master branch to production at all times. All branches are assumed to be active; stale branches should get cleaned up accordingly.

### Projects in initial development

Projects that aren't live yet have at least two branches: `master` and `dev`. Avoid committing directly on the master branch, always commit through dev.

Feature branches are optional, if you'd like to create a feature branch, make sure it's branched from `dev`, not `master`.

### Branch naming

There's no strict ruling on feature branch names, just make sure it's clear enough to know what they're for. Branches may only contain lowercase letters and hyphens.

- Bad: `updates-june-2016`, `develop`
- Good: `feature-mailchimp`, `fix-deliverycosts` or `feature/mailchimp`

### Merge requests

Merging branches via Gitlab merge requests is a requirement, can be useful for:

- You want a peer to review your changes
- You want to ensure your branch can be merged and commits can be squashed via an interface
- Future you wants a quick way to retrieve that point in history by browsing passed pull requests

### Merging and rebasing

Ideally, rebase your branch from the origin regularly to reduce the chance of merge conflicts.
This is also helpfull when someone releases content onto the main branch that you may require

- Rebase your branch using `git rebase <branch-origin>` (if you branched off dev, rebase from dev)
- If you want to deploy a feature branch to `master` or `dev`, use `git merge <branch>` (`--squash` if you wish to assemble all the commits into one)

## Commits

We try to enforce a bit of strict ruling on commits in projects of any nature. 

- Separate subject from body with a blank line
- Rule of thumb: Limit the subject line to 50 characters (more or less, if you can't summarize then maybe your commit should be smaller)
- Capitalize the subject line
- Do not end the subject line with a period
- Use the imperative mood in the subject line
- Wrap the body at 72 characters (we can reach up to 100, its for better readability of whoever is reading)
- Use the body to explain what and why vs. how
- Leave a task link in the end of the commit

Ideally:

```
Summarize changes in around 50 characters or less

More detailed explanatory text, if necessary. Wrap it to about 72
characters or so. In some contexts, the first line is treated as the
subject of the commit and the rest of the text as the body. The
blank line separating the summary from the body is critical (unless
you omit the body entirely); various tools like `log`, `shortlog`
and `rebase` can get confused if you run the two together.

Explain the problem that this commit is solving. Focus on why you
are making this change as opposed to how (the code explains that).
Are there side effects or other unintuitive consequences of this
change? Here's the place to explain them.

Further paragraphs come after blank lines.

 - Bullet points are okay, too

 - Typically a hyphen or asterisk is used for the bullet, preceded
   by a single space, with blank lines in between, but conventions
   vary here

If you use an issue tracker, put references to them at the bottom,
like this:

Tasks: IDD-313
https://zlx.atlassian.com/task-id
```

## Git Tips

### Creating granular commits with `patch`

If you've made multiple changes but want to split them into more granular commits, use `git add -p`. This will open an interactive session in which you can choose which chunks you want to stage for your commit.

### Forbidden techniques

Only execute when requested by the senior developers and when you are sure that no-one else pushed changes during your commits.
Other than that, <u><i>never EVER use the force</i></u>

```
git push --force
```

### Cleaning up local branches

After a while, you'll end up with a few stale branches in your local repository. Branches that don't exist upstream can be cleaned up with `git remote prune origin`. If you want to ensure you're not about to delete something important, add a `--dry-run` flag.

## Resources

- Merge vs. rebase on [Atlassian](https://www.atlassian.com/git/tutorials/merging-vs-rebasing/workflow-walkthrough)
