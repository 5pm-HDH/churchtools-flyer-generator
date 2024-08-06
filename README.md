# ChurchTools Flyer Generator

The ChurchTools Flyer Generator is a public service for the creation of flyers and other images that are filled with data from ChurchTools.

https://flyer-generator.5pm.zone/

🔭 **Fundamental philosophy:** No one wants to share their ChurchTools credentials with any other platform than ChurchTools. This project follows the approach of being completely open. There is no user registration or login required.  Since most ChurchTools calendars are public and available without logging in, this approach might actually work.

💡 The idea for this project comes from a proof of concept implementation described in [this blog post](https://tech.5pm.zone/2024/08/01/proof-of-concept-flyer-generator/)

## Run this Project (Development)

This project is developed with the [Laravel-Framework](https://laravel.com/) any questions about the configuration should be answered in the Laravel Documentation.

1. Download sourcecode
2. Install PHP, Composer and Node.js if not already done
3. Copy the `.env.example` file to `.env`
4. Create a file named `database.sqlite` in the project root
5. Run `composer install` to install all backend dependencies
6. Run `php artisan migrate` to create the sqlite-database
7. Run `php artisan serve` to start up the server and open the displayed address in the browser

## Roadmap of this Project

This repo provides in the current state only a template to build further development on it. 

**Stage 1: ✅**

Create flyer for appointments.

- [x] Select multiple appointments from calendar to create flyer for. Filter for calendar, start- and end-date.
- [x] Provide a basic set off template variables (caption, description, start- / end-date, ...); Templated variables are formatted as v-chips.
- [x] Upload word-file as template (see [implementation in poc](https://github.com/5pm-HDH/churchtools-cli/blob/827cb552e0975d0d848b3e10b568ee9737bbd663/src/Commands/ExportCommands/ExportFlyerCommand.php#L35))
- [x] Download processed word-files (finished flyers).
- [x] Deploy public service to demonstrate usage.

**Stage 2:**

TBD 

## Discussion and remaining challenges

- What template format is suitable other than word?
- What export format is required to provide a benefit for the user? The user in some cases should be able to customize the output.
- What other ChurchTools data entities are useful to create flyer or other image data from? Is there a requirement for groups, e.q. Gemeindefreizeit.
