<?php include "header.html";?>
<div id = "article">
	<div class = "article-heading">Development Resources</div>
	<div class = "text">
	<b>For the following commands you need to add "mesecons" to the dependencies of your mod</b><br/><br/>
	<div class = "paragraph-heading">General information</div>
	There are 3 basic node types that you need to know about for Mesecon development.<br/>
	You usually define two different nodes for each receptor / conductor / effector:
	<li>An active one, that emits / conducts / receives energy, its state is mesecon.state.on</li>
	<li>An inactive one, that doesn't emit / conduct / receive energy, its state is mesecon.state.off</li><br/>
	Every definiton of a mesecon items also contains "rules". Rules define what positions the node connects to. For instance, normal mesecons connect to all nodes around them at the same height, above or below. Vertical mesecons only connect to those nodes that are above or below.<br/>
	There are some preset rules:
	<li>mesecon.rules.default The standard rules, used if no rules are specified.</li>
	<li>mesecon.rules.buttonlike The rules that buttons and wall levers use.</li>
	<li>mesecon.rules.flat Node only connects to others at the same height (e.g. Microcontroller)</li><br/>
	You can also make rules yourself, it is even possible to make rules dependent on the orientation of the node: See the "Rules" section below.
	The definition of a node includes a mesecons = {...} field that contains all the code for the mesecons integration.
	<br/><br/>

	<div class = "paragraph-heading">Conductors</div>
	<img src="textures/mesecon.png" style = "float:right; height: 160px;">
	<div class = "code">
		minetest.register_node("any:name", {<br/>
		&nbsp;&nbsp;...<br/>
		&nbsp;&nbsp;mesecons = {conductor = {<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;state = mesecon.state.on/off<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;onstate = "any:name_on"<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;onstate = "any:name_off"<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;rules = mesecon.rules<br/>
		&nbsp;&nbsp;}}<br/>
		})<br/>
	</div>
	<b>state</b> = a mesecon.state | Defines if the conductor is active or inactive<br/>
	<b>onstate</b> = a nodename | Required if state == mesecon.state.off | Defines the other nodename of the conductor definition, the active one.<br/>
	<b>offstate</b> = a nodename | Required if state == mesecon.state.on | Defines the other nodename of the conductor definition, the inactive one.<br/>
	<b>rules</b> = a mesecon.rules | If not specified, is mesecon.rules.default | Defines the connection rules of the conductor | Can be a function or a rules table
	<br/><br/>


	<div class = "paragraph-heading">Receptors</div>
	Receptors are nodes that <i>send out</i> mesecon energy.
	<img src="textures/switch.png" style = "float:right; height: 160px;">
	<div class = "code">
		minetest.register_node("any:name", {<br/>
		&nbsp;&nbsp;...<br/>
		&nbsp;&nbsp;mesecons = {receptor = {<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;state = mesecon.state.on/off<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;rules = mesecon.rules<br/>
		&nbsp;&nbsp;}}<br/>
		})<br/>
	</div>
	<b>state</b> = a mesecon.state | Defines if the receptor is active or inactive<br/>
	<b>rules</b> = a mesecon.rules | If not specified, is mesecon.rules.default | Defines the connection rules of the receptor | Can be a function or a rules table
	<br/>

	<br/>
	If a receptor recognized something and you want it to turn on, you have to call
	<div class = "code">
	minetest.env:add_node(pos, {name="myreceptor:receptor_on"})
	mesecon:receptor_on(pos, rules)
	</div>
	...to turn it off again:
	<div class = "code">
	minetest.env:add_node(pos, {name="myreceptor:receptor_off"})
	mesecon:receptor_off(pos, rules)
	</div>
	<br/><br/>



	<div class = "paragraph-heading">Effectors</div>
	Effectors do something when they receive power.
	<img src="textures/lightstone_blue.png" style = "float:right; height: 160px;">
	<div class = "code">
		minetest.register_node("any:name", {<br/>
		&nbsp;&nbsp;...<br/>
		&nbsp;&nbsp;mesecons = {effector = {<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;rules = mesecon.rules<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;action_on = function (pos, node)<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;action_off = function (pos, node)<br/>
		&nbsp;&nbsp;&nbsp;&nbsp;action_change = function (pos, node)<br/>
		&nbsp;&nbsp;}}<br/>
		})<br/>
	</div>
	<b>rules</b> = a mesecon.rules | If not specified, is mesecon.rules.default | Defines the connection rules of the effector | Can be a function or a rules table<br/>
	<b>action_on</b> a function (pos, node) | It is called when the effector receives power and is not powered by anything else<br/>
	<b>action_off</b> a function (pos, node) | It is called when the effector doesn't receive power anymore and is not powered by anything else<br/>
	<b>action_change</b> a function (pos, node) | Called if action_on or action_off are called, but also if the receptor is already being powered and a change occurs somewhere (e.g. used by microcontroller)<br/>
	<br/>


	<div class = "paragraph-heading">Rules</div>
	Rules are a table of relative positions that describe what nodes around an effector/receptor/conductor it links to, e.g. where a receptor turns on conductors.<br/><br/>

	<div class = "code">
	myrules =, <br/>
	{{x=-1,  y=0,  z=0},<br/>
	&nbsp;{x=1,  y=0,  z=0}}<br/>
	</div>
	These rules link to the nodes at the x+ side and x- side of the effector/receptor/conductor.
	<br/><br/>
	There are some cases in which the rules depend on the rotation of a node, e.g. for the delayer.
	In this case you just use a function instead of a table:
	<br/><br/>
	<div class = "code">
	rules = function(node)<br/>
	&nbsp;&nbsp;if node.param2 == 1 then<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;return {{x=1, y=0, z=0}}<br/>
	&nbsp;&nbsp;elseif mode.param2 == 2 then<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;...<br/>
	&nbsp;&nbsp;end<br/>
	end
	</div>
	</div>


	<div class = "paragraph-heading">Complex items</div>
	Complex items like the microcontroller, gates, the dealyer or the torch.

	<br/><br/>
	<div class = "paragraph-heading">Design</div>
	VanessaE is the design leader of mesecons. Ask her for detailed information about mesecon design.<br/>
	This shows the colors and the proportions of a mesecon wire:
	<img src = "img/meseconsdesign.png"><br/>
	The default texture size is 16x16. In special cases you may use 32x32 (e.g. Microcontroller), but only make the important parts of the texture in that resolution (for the Microcontroller it is only the letters at the input ports) and keep the rest in 16px which means 2x2 pixels.<br/>

	<br/><br/>
	<div class = "paragraph-heading">Contribute</div>
	You contribute by forking https://github.com/Jeija/minetest-mod-mesecons and sending a pull request.
</div>
<?php include "footer.html";?>
