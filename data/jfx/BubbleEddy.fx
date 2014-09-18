import javafx.animation.*;
import javafx.scene.*;
import javafx.scene.input.*;
import javafx.scene.paint.*;
import javafx.scene.shape.*;
import javafx.scene.transform.*;
import javafx.stage.*;

public class BubbleEddy extends CustomNode {
    def size = 50;
    var circles: Circle[] = [for (i in [0..size-1]) {
        var rand = new java.util.Random();
        var scale = rand.nextDouble();
        Circle {
          transforms: Scale {
            x : scale, y : scale
          }
          centerX: 10
          centerY: 10
          opacity: i/(size*.6),
          radius: (size-i)*0.5,
          fill: RadialGradient {
            centerX: 0.25
            centerY: 0.25
            stops: [
                Stop {
                  offset: 0.0
                  color: Color.WHITE
                },
                Stop {
                  offset: 0.6
                  color: Color.SKYBLUE
                }]
          }
        }
    }];
    var timeline = Timeline {
      repeatCount: Timeline.INDEFINITE
      keyFrames : [ KeyFrame {
        time: 25ms;
        action: function() { follow(); }
      }]
    };
    function follow(): Void {
        for(i in [1..size-1]) {
            circles[i-1].centerX = circles[i].centerX;
            circles[i-1].centerY = circles[i].centerY;
        }
    }
    override public function create(): Node {
        timeline.play();
        return Group {
          content: [ Rectangle {
            onMouseMoved: function(e:MouseEvent):Void {
                circles[(sizeof circles)-1].centerX = e.x;
                circles[(sizeof circles)-1].centerY = e.y;
            },
            width: 320, height: 240
            fill:Color.MIDNIGHTBLUE
          }, circles ]
        };
    }
}
function run() {
    Stage {
      scene: Scene { content: BubbleEddy{} }
      title  : "JavaFX - Bubble Eddy"
      width  : 320, height : 240
      resizable: false
      visible: true
    }
}
