<h1>Kelly Redbook done by Mason, Sean, Blessing, and Jaeden </h1>

<h2>Design and implementation assumptions:</h2>
<ul>
<li>Password reset not implemented</li>
<li>Only price cars that exists in the database</li>
</ul>

<h2>Challenges faced:</h2>
<ul>
<li>We originally planned to use checkboxes, but we kept getting errors when testing so we decided to use radio checkboxes instead. </li>
<li>We originally had the final prices recorded inside the database, but we decided to change it so the program does the calculations when their respective cars are price and just the original adjusted price is recorded.</li>
</ul>

<h2>Required:</h2>
We have a way to add a car to the database, a way to record users, a way to log in, a way to sign up, a way to log out, and a way to view the user’s cars. We also have a homepage the users get sent to.

<h2>Known bugs:</h2>
<ul>
<li>A bug we did have before was the checkboxes not working but we changed that to radio checkboxes.</li>
<li>Duplicate cars are not saved. Cant have multiple cars of the same make, model and year in the inventory</li>
</ul>

<h2>Testing procedures</h2>
When testing the program, we did a variety of steps. The first step was to obviously sign up a user. Once they were added into the database, we would Login using the given email and password from before. Once logged in, users can go to a page to add a new car entity into the database. Once successfully added, users can then go to a page to see all the cars in their collection. Once finished the user can then log out, returning the user back to the home page and able to log back in again or create a new account.