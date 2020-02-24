<h1>Classic installation procedure</h1>

<ol>
    <li>Fork/Clone the project.</li>
    <li><code>composer install</code>.</li>
    <li>Configure your .env.local file.</li>
    <li><code>yarn install</code> or <code>npm install</code>.</li>
    <li><code>php bin/console doctrine:database:create</code>.</li>
    <li><code>php bin/console doctrine:migrations:migrate</code>.</li>
    <li><code>php bin/console doctrine:fixtures:load</code>.</li>
</ol>

<p>
    Let's go with a <code>yarn build</code> and a <code>symfony serve</code> !
</p>

<p>Thanks to <a href="www.margauxpassot.com">Margaux Passot</a> for the design.</p>