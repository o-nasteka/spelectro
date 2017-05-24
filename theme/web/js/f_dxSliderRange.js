/**
 * Libreria para mostrar un slider con dos rangos
 */
 

var dxSliderRange = new Class({
    options: {
        onChange: Class.empty,
        onComplete: Class.empty,
        onTick: function (a) {
            this.moveKnob.setStyle(this.p, a)
        },
        start: 0,
        end: 100,
        offset: 8,
        knobheight: 20,
        knobwidth: 14,
        mode: 'horizontal',
        clip_w: 0,
        clip_l: 0,
        isinit: true,
        snap: false,
        range: false,
        numsteps: null,
        input_desde: null,
        input_hasta: null,
        concatenar: " €",
		completeDrag: Class.empty
    },
    initialize: function (a, b, c, d, e) {
        if (!$(a)) return false;
        this.element = $(a);
        aAux1 = this.element.getProperty("rel").split(",");
        aIF = aAux1[0].split("_");
        aMM = aAux1[1].split("_");
        d.start = aIF[0].toInt();
        d.end = aIF[1].toInt();
        this.setOptions(d);
        this.knob = $(b);
        this.previousChange = this.previousEnd = this.step = -1;
        this.bkg = $(c);
        if (this.options.steps == null) {
            this.options.steps = this.options.end - this.options.start
        }
        if (e != null) this.maxknob = $(e);
        var f, offset;
        switch (this.options.mode) {
        case 'horizontal':
            this.z = 'x';
            this.p = 'left';
            f = {
                'x': 'left',
                'y': false
            };
            offset = 'offsetWidth';
            break;
        case 'vertical':
            this.z = 'y';
            this.p = 'top';
            f = {
                'x': false,
                'y': 'top'
            };
            offset = 'offsetHeight'
        }
        this.dmMin = new Element("div", {
            "class": "slde-rnge-tip",
            "html": '<span></span><div class="slde-rnge-tip-flxa"></div>'
        });
        this.dmMin.injectInside(this.element);
        this.dmMax = new Element("div", {
            "class": "slde-rnge-tip",
            "html": '<span></span><div class="slde-rnge-tip-flxa"></div>'
        });
        this.dmMax.injectInside(this.element);
        this.max = this.element[offset] - this.knob[offset] + (this.options.offset * 2);
        this.half = this.knob[offset] / 2;
        this.full = this.element[offset] - this.knob[offset] + (this.options.offset * 2);
        this.min = $chk(this.options.range[0]) ? this.options.range[0] : 0;
        this.getPos = this.element['get' + this.p.capitalize()].bind(this.element);
        this.knob.setStyle('position', 'relative').setStyle(this.p, -this.options.offset);
        this.range = this.max - this.min;
        this.steps = this.options.steps || this.full;
        this.stepSize = Math.abs(this.range) / this.steps;
        this.stepWidth = this.stepSize * this.full / Math.abs(this.range);
        if (e != null) {
            this.maxPreviousChange = -1;
            this.maxPreviousEnd = -1;
            this.maxstep = this.options.end;
            this.maxknob.setStyle('position', 'relative').setStyle(this.p, +this.max - this.options.offset).setStyle('bottom', this.options.knobheight)
        }
		
        var g = {};
        g[this.z] = [-this.options.offset, this.max - this.options.offset];
        this.drag = new Drag(this.knob, {
            limit: g,
            modifiers: f,
            snap: 0,
            onStart: function () {
                this.draggedKnob()
            }.bind(this),
            onDrag: function () {
                this.draggedKnob()
            }.bind(this),
            onComplete: function () {
				if( $type( this.options.completeDrag ) == "function" )
					this.options.completeDrag();

                this.draggedKnob();
                this.end()
            }.bind(this)
        });
        if (e != null) {
			this.maxknob.addEvent("mousedown",function()
			{
				this.dmMax.setStyle("zIndex", 10 );
				this.dmMin.setStyle("zIndex", 9 );
			}.bind(this));

			this.knob.addEvent("mousedown",function()
			{
				this.dmMax.setStyle("zIndex", 9 );
				this.dmMin.setStyle("zIndex", 10 );
			}.bind(this));
			
            this.maxdrag = new Drag(this.maxknob, {
                limit: g,
                modifiers: f,
                snap: 0,
                onStart: function () {
                    this.draggedKnob(1)
                }.bind(this),
                onDrag: function () {
                    this.draggedKnob(1)
                }.bind(this),
                onComplete: function () {
					if( $type( this.options.completeDrag ) == "function" )
						this.options.completeDrag();

                    this.draggedKnob(1);
                    this.end()
                }.bind(this)
            })
        }
        if (this.options.snap) {
            this.drag.options.grid = (this.full) / this.options.numsteps;
            this.drag.options.limit[this.z][1] = this.full;
            status = "GRID - " + this.drag.options.grid + "  , full = " + this.full
        }
        if (this.options.initialize) this.options.initialize.call(this);
        this.setMin(aMM[0].toInt());
        this.setMax(aMM[1].toInt())
    },
    setMin: function (a) {
        this.step = a.limit(this.options.start, this.options.end);
        this.checkStep();
        this.end();
        this.moveKnob = this.knob;
        this.bkg.style.clip = "rect(0px " + (parseInt(this.toPosition(this.step)) + 3) + "px 10px 0px)";
        status = this.bkg.style.clip + "  vl= " + parseInt(this.toPosition(this.step));
        this.fireEvent('onTick', this.toPosition(this.step));
        return this
    },
    setMax: function (a) {
        this.maxstep = a.limit(this.options.start, this.options.end);
        this.checkStep(1);
        this.end();
        this.moveKnob = this.maxknob;
        var w = Math.abs(this.toPosition(this.step) - this.toPosition(this.maxstep)) + 3;
        var r = parseInt(this.clip_l + w);
        this.bkg.style.clip = "rect(0px " + r + "px 10px " + this.clip_l + "px)";
        this.fireEvent('onTick', this.toPosition(this.maxstep));
        if (this.options.isinit) {
            var b = {};
            var c, mx;
            c = -this.options.offset;
            mx = parseInt(this.maxknob.getStyle('left')) - this.options.offset - 4;
            b[this.z] = [c, mx];
            this.drag.options.limit = b;
            this.options.isinit = false
        }
        this.dmMax.setStyles({
            "left": this.maxknob.getStyle("left").toInt() - (this.dmMax.getSize().x / 2) + (this.maxknob.getSize().x / 2)
        });
        return this
    },
    draggedKnob: function (a) {
        var b = {};
        var c, a;
        if (a == null) {
            this.step = this.toStep(this.drag.value.now[this.z]);
            this.checkStep()
        } else {
            this.maxstep = this.toStep(this.maxdrag.value.now[this.z]);
            this.checkStep(1)
        }
    },
    checkStep: function (a) {
        var b = a;
        var c = {};
        var d, a;
        var e = {};
        if (a == null) {
            if (this.previousChange != this.step) {
                this.previousChange = this.step
            }
        } else {
            if (this.maxPreviousChange != this.maxstep) {
                this.maxPreviousChange = this.maxstep
            }
        }		
		
        if (this.maxknob != null) {
            d = -this.options.offset;
            a = parseInt(this.maxknob.getStyle('left')) - this.options.offset - 4;
            c[this.z] = [d, a];
            this.drag.options.limit = c;
            d = parseInt(this.knob.getStyle('left')) - this.options.offset + 22;
            a = this.max - this.options.offset;
            e[this.z] = [d, a];
            this.maxdrag.options.limit = e;
            if (this.step < this.maxstep) {
                this.fireEvent('onChange', {
                    minpos: this.step,
                    maxpos: this.maxstep
                });
                this.dmMin.getElement("span").set("text", this.step + this.options.concatenar);
                this.dmMin.setStyles({
                    "left": this.knob.getStyle("left").toInt() - (this.dmMin.getSize().x / 2) + (this.knob.getSize().x / 2)
                });
                this.dmMin.getElement("div").setStyle("left", (this.dmMin.getSize().x / 2) - (this.dmMin.getElement("div").getSize().x / 2));
                this.options.input_desde.set("value", this.step);
				
                this.dmMax.getElement("span").set("text", this.maxstep + this.options.concatenar);
                this.dmMax.setStyles({
                    "left": this.maxknob.getStyle("left").toInt() - (this.dmMax.getSize().x / 2) + (this.maxknob.getSize().x / 2)
                });
                this.dmMax.getElement("div").setStyle("left", (this.dmMax.getSize().x / 2) - (this.dmMax.getElement("div").getSize().x / 2));
                this.options.input_hasta.set("value", this.maxstep)
            } else {

				this.dmMin.setStyles({
					"left": this.knob.getStyle("left").toInt() - (this.dmMin.getSize().x / 2) + (this.knob.getSize().x / 2)
				});
				this.dmMin.getElement("div").setStyle("left", (this.dmMin.getSize().x / 2) - (this.dmMin.getElement("div").getSize().x / 2));
		
				this.dmMax.setStyles({
                    "left": this.maxknob.getStyle("left").toInt() - (this.dmMax.getSize().x / 2) + (this.maxknob.getSize().x / 2)
                });
                this.dmMax.getElement("div").setStyle("left", (this.dmMax.getSize().x / 2) - (this.dmMax.getElement("div").getSize().x / 2))
			
                this.fireEvent('onChange', {
                    minpos: this.maxstep,
                    maxpos: this.step
                })
            }
            this.clip_l = parseInt(this.knob.getStyle('left')) + 10;
            var w = Math.abs(parseInt(this.knob.getStyle('left')) - parseInt(this.maxknob.getStyle('left')));
            var r = parseInt(this.clip_l + w);
            this.bkg.style.clip = "rect(0px " + r + "px 10px " + this.clip_l + "px)"
        } else {
            this.fireEvent('onChange', this.step);
            this.bkg.style.clip = "rect(0px " + (parseInt(this.drag.value.now[this.z]) + 3) + "px 10px 0px)"
        }
    },
    end: function () {
        if (this.previousEnd !== this.step || (this.maxknob != null && this.maxPreviousEnd != this.maxstep)) {
            this.previousEnd = this.step;
            if (this.maxknob != null) {
                this.maxPreviousEnd = this.maxstep;
                if (this.step < this.maxstep) this.fireEvent('onComplete', {
                    minpos: this.step + '',
                    maxpos: this.maxstep + ''
                });
                else this.fireEvent('onComplete', {
                    minpos: this.maxstep + '',
                    maxpos: this.step + ''
                })
            } else {
                this.fireEvent('onComplete', this.step + '')
            }
        }
    },
    toStep: function (a) {
        return Math.round((a + this.options.offset) / this.max * this.options.steps) + this.options.start
    },
    toPosition: function (a) {
        return (this.max * a / this.options.steps) - (this.max * this.options.start / this.options.steps) - this.options.offset
    }
});
dxSliderRange.implement(new Events);
dxSliderRange.implement(new Options);