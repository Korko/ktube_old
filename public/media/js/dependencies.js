var Dependencies = (function() {

	var scripts = {};
	var deps = {};

	this.add = function(alias, path, deps) {
		scripts[alias] = {path: path, deps: deps};
		return this;
	};

	this.init = function() {

		// Get dependencies
		deps = getDeps();

		// Fetch all scripts
		for(var scriptName in scripts) {
			if(scripts.hasOwnProperty(scriptName)) {
				(function(scriptName) {
					fetchScript(scripts[scriptName].path, function() {

						scripts[scriptName].content = this.responseText;

						// Mark the script as fetched
						scripts[scriptName].fetched = true;

						// Check deps and if ready, load it
						loadIfReady(scriptName);

					});
				})(scriptName);
			}
		}

	};

	this.getDeps = function() {

		var deps = {};
		for(var scriptName in scripts) {
			if(scripts.hasOwnProperty(scriptName) && scripts[scriptName].deps) {
				for(var depKey in scripts[scriptName].deps) {

					var dep = scripts[scriptName].deps[depKey];
					if(!deps[dep]) deps[dep] = [];
					deps[dep].push(scriptName);

				}
			}
		}

		return deps;

	};

	this.loadIfReady = function(scriptName) {

		// Check if required scripts are loaded
		var ready = true;
		if(scripts[scriptName].deps) {
			for(var depKey in scripts[scriptName].deps) {
				var dep = scripts[scriptName].deps[depKey];
				if(!scripts[dep].loaded) {
					ready = false;
				}
			}
		}

		// If ready, load
		if(ready) {
			loadScript(scriptName);
		}

	};

	this.loadScript = function(scriptName) {

		// Insert the script in the DOM
		eval(scripts[scriptName].content);

		// Mark the script as loaded
		scripts[scriptName].loaded = true;

		// Check if there was scripts needed by this one
		if(deps[scriptName]) {
			for(var depKey in deps[scriptName]) {
				// For each script needing this one which is fetched, check all dependencies
				var dep = deps[scriptName][depKey];
				if(scripts[dep].fetched && !scripts[dep].loaded) {
					loadIfReady(dep);
				}
			}
		}

	};

	this.fetchScript = function(path, onload) {

		var req = new XMLHttpRequest();
		req.open("GET", path);
		req.onload = onload;
		req.send();

	};

	return {
		add: this.add,
		init: this.init
	};

})();
