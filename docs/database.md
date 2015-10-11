# Database Component

## Modules

- **Structured Migrations**: A much different approach to migrations in
Laravel. Migrations are organized in classes (PSR-2 compatible), which makes it
 easy to organize things neatly, performed advanced verification through
 introspection, or display a GUI with the status of migrations. The main con
 with this approach is how non-standard it is and that migration files will no
 longer be sorted in the order they are applied.
- **Namespaced Migrations**: Sort of the precursor to Structured Migrations. A
small hack to put regular Laravel migrations inside a namespace. It has no
benefit other than neat directory structure. Might be deprecated in the future.

## Utility Classes

- **BaseMigration**: A simple migration which includes an internal reference to
the current connection, the schema builder and some utility classes. This make
it possible to avoid using the `Schema::` facade, which makes IDEs happy even
though it's not functionally that different.
- **TableMigration**: A simplification of the most common migration: Create a
table on `up()` and drop it on `down()`. Simply provide a table name and
implement the `create()` method and you're good to go. It's benefit becomes
apparent when you have 20+ migrations doing the exact same thing.
