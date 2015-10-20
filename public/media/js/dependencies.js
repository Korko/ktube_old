var Dependencies = (function() {

	var scripts = {};

	this.add = function(alias, path, deps) {
		scripts[alias] = {path: path, deps: deps};
		return this;
	};

	this.load = function() {

		var deps = this.getDeps();


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

	}

	this.fetch = function(path, onload) {

		var req = new XMLHttpRequest();
		req.open("GET", path);
		req.onload = onload;
		req.send();

	};

	return {
		add: this.add,
		load: this.load
	};

})();
