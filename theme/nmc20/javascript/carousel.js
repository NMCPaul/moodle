function homepageAnimation(elementId, args)
{
  if(!args.speed)
  {
    this.speed = 6000;
  } else
  {
    this.speed = args.speed;
  }
  this.tween = false;
  this.interval = false;
  this.lastSlide = 0;
  this.slides = new Array();

  this.init = function(id)
  {
    var container = document.getElementById(id);

    for(var i = 0; i < container.childNodes.length; i++)
    {
      if(container.childNodes[i].nodeType == 1 && container.childNodes[i].nodeName.toLowerCase() != 'script')
        this.addSlide(container.childNodes[i]);
    }

    this.set(0);
    this.play();
  }
  
  this.addSlide = function(element)
  {
    var self = this;
    element.style.zIndex = 1000 - this.slides.length;
    element.onmouseover = function() { self.pause(); }
    element.onmouseout = function() { self.play(); }
    this.slides.push(element);
  }
  
  this.set = function(slide)
  {
    if(this.interval)
    {
      clearInterval(this.interval);
      this.interval = false;
    }
    if(this.tween)
      this.tween.stop();

    this.slides[0].style.zIndex = 1000;
    for(var i = 0; i < this.slides.length; i++)
      this.slides[i].style.visibility = (i == slide) ? "visible" : "hidden";

    this.clearOpacity(this.slides[slide]);
  }

  this.nextSlide = function()
  {
    var self = this;
  
    var finalSlide = this.lastSlide == (this.slides.length-1);
    if(finalSlide)
      this.slides[0].style.zIndex = 1000 - this.slides.length;

    this.tween = new OpacityTween(this.slides[this.lastSlide], Tween.regularEaseOut, 100, 0, 1);
    this.tween.start();
    
    var obj = this.slides[this.lastSlide];
    
    if(finalSlide)
    {
      this.lastSlide = 0;
      this.tween.onMotionFinished = function()
      {
        obj.style.visibility = "hidden";
        self.slides[0].style.zIndex = 1000;
        self.clearOpacity(obj);
      }
    }
    else
    {
      this.lastSlide++;
      this.tween.onMotionFinished = function()
      {
        obj.style.visibility = "hidden";
        self.clearOpacity(obj);
      }
    }

    this.slides[this.lastSlide].style.visibility = "visible";
  }
  
  this.clearOpacity = function(o)
  {
    o.style.opacity = "";
    o.style.MozOpacity = "";
    o.style.KhtmlOpacity = "";
    o.style.filter = "";
  }
  
  this.play = function()
  {
    if(!this.interval)
    {
      var self = this;
      this.interval = setInterval(function() { self.nextSlide() }, this.speed);
    }
  }

  this.pause = function()
  {
    if(this.interval)
    {
      clearInterval(this.interval);
      this.interval = false;
    }
  }
  
  this.init(elementId);
}
