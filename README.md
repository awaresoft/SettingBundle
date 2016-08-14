<h1>Awaresoft SettingBundle</h1>

<i>Remember! This vendor library is currently use in many projects. Please ensure that your changes are backward compatible.</i>

<h2>Rules of modifying this vendor library</h2>
<ol>
    <li>If you add new modification, you must ensure backward compatibility.</li>
    <li>Adding new version to Git repository, you must send new tag</li>
    <li>If your modification is:
        <ul>
            <li>hotfix - increment last number e.g. 1.0.0 -> 1.0.1</li>
            <li>feature - increment second number e.g. 1.0.0 -> 1.1.0</li>
            <li>new version - a lot of modification which breaks BC, increment first number: 1.0.0 -> 2.0.0</li>
        </ul>
    </li>
</ol>

<h2>Instalation of vendor in local environment</h2>
<p>If you want to copy repository to your local storage, you have 2 ways:</p>
<ul>
    <li>Each project should contain <b>utils/prepare_vendors</b> script, which download vendor repositories from Git and link to project. Run it.</li>
    <li>If you want to copy only one vendor, create vendor directory and clone Git repository directly from there. Remember, if you want to use this vendor in your project, you have to manually create symlink to directory <b>/src/Awaresoft</b>.</li>
</ul>

<h2>Modify vendor library and update origin version</h2>
<ul>
    <li>Always modify this library directly from your project. Vendor should be symlinked to <b>/src/Awaresoft</b> directory in your project.</li>
    <li>If you install this library before by Composer, you must remove this package from <b>/vendor</b> directory. After this operation got to: <b>/vendor/composer/autoload_psr4.php</b> file, find connection to this library and remove it from there.</li>
    <li>After that you can refresh your cache by: <b>app/console ca:cl</b> command.
    <li>To commit new changes in library, use:
<pre>
git add .
git commit -m "[message]"
</pre>
    <li>Add new tag, corresponding to naming convention of tags: Check if tag is available, before add new one:</li>
<pre>
git tag [x.x.x]
</pre>
    <li>Push your changes:</li>
<pre>
git push
git push --tags
</pre>
    </li>
    <li>Wait for update of vendors' libraries by Satis. You can check if new version is added on: <b>satis.awaresoft.pl</b>.</li>
    <li>Update composer repositories of your project by: <b>composer update</b> command.</li>
</ul>
