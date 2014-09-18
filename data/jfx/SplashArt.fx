import javafx.animation.*;
import javafx.scene.*;
import javafx.scene.input.*;
import javafx.scene.paint.*;
import javafx.scene.shape.*;
import javafx.stage.*;
import java.util.Random;

public class SplashArt extends CustomNode {
    var circles: Circle[] = [ for(i in [0..50]) Circle{
      fill: Color.YELLOW
      cache: true,
      blocksMouse: true,
      focusable: false,
    }];
    var timeline = Timeline {
      repeatCount: Timeline.INDEFINITE
      keyFrames : [ KeyFrame {
        time: 50ms;
        action: function() { follow(); }
      }]
    };
    var rnd: Random = new Random();
    var x = -100.0;
    var y = -100.0;
    function follow(): Void {
        var d:Integer = rnd.nextInt(6);
        var cc = circles[(sizeof circles)-1];
        delete circles[(sizeof circles)-1];
        cc.centerX = if(d mod 2 == 0) x-d else x+d;
        cc.centerY = y;
        cc.opacity = 1.0;
        cc.radius  = 2;
        insert cc before circles[0];
        for(i in [1..(sizeof circles)-1]) {
            circles[i].opacity -= 0.04;
            circles[i].radius  += i*0.1;
            circles[i].centerY -= i*0.3;
        }
    }
    override public function create(): Node {
        timeline.play();
        return Group {
          onMouseMoved: function(e:MouseEvent):Void {
              x = e.x; y = e.y;
          },
          content: bind [ Rectangle {
            width: 320, height: 240
            fill:Color.MIDNIGHTBLUE
          }, circles ]
        };
    }
}
function run() {
    Stage {
      scene: Scene { content: SplashArt{} }
      title: "JavaFX - Splash Art"
      width: 320, height : 240
      resizable: false
      visible: true
    }
}
