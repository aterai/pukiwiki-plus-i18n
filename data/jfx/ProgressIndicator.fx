import javafx.animation.*;
import javafx.scene.*;
import javafx.scene.image.*;
import javafx.scene.paint.*;
import javafx.scene.shape.*;
import javafx.scene.transform.*;
import javafx.stage.*;

public class ProgressIndicator extends CustomNode {
    public var x: Number;
    public var y: Number;
    var radius:   Number = 6;
    var rotation: Number = 0;
    var r: Number = 2*radius;
    var centerX: Number = 2*r+5;
    var centerY: Number = 2*r+5;
    var timeline: Timeline = Timeline {
        repeatCount: Timeline.INDEFINITE
        keyFrames: [
            KeyFrame {
                time: 0s
                values: [ rotation => 0 ]
            }, KeyFrame {
                time: 800ms
                values: [ rotation => 8 tween Interpolator.LINEAR ]
            }
        ]
    }
    override public function create(): Node {
        timeline.play();
        return Group {
          translateX: x+centerX, translateY: y+centerY,
          content: for (i in [1..8]) Circle {
            transforms: bind [
                Translate { x : -r,  y : -r },
                Rotate { pivotX : r, pivotY : r, angle: (i+rotation)*45.0 } ],
            radius: radius,
            fill: Color.GREEN,
            opacity: 0.1*i,
          }
        };
    }
}
function run() {
    Stage {
      scene: Scene {content: [ ProgressIndicator{x: 100, y: 10} ] }
      title  : "JavaFX - Progress Indicator"
      width  : 320
      height : 240
      visible: true
    }
}
