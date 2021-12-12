# README.md

Custom module assignment for Digitalist.

## Assignment

```
The task is to create a page in Drupal 9 where there is a text field and a search button. I couldfor example be a page on the url /search-beverage.

When the user has entered a word to search and hit the search button a list with a search resultshould be displayed.

The search should go against this endpoint:
http://systembevakningsagenten.se/api/json/1.0/searchProduct.json?query=[SEARCHWORD]
Where [SEARCHWORD] is replaced with for example Sigtuna and results are shown.

The search should be packaged as a module that could be used on any Drupal 9 site.

The search form does not need to be themed with css but the theme/css the site is running on is good enough.

If more than 5 results are shown a pager should show up.
```

## Notes

Although the assignment could be solved in different levels of complexity I decided to overdo it just for fun and to use
a bit more of what Drupal offers us. For example:

- API URL is configurable via Admin form, as well as Number of items per page.
- BeveragesApi has been defined as a service when maybe it was not completely necessary.
- Templating for results. What properties needed to be shown was not specified, so a small list of names could have been
  displayed using just the Form class.
